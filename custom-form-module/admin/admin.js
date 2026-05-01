/**
 * Admin DataTable Initialization and Handlers
 */

jQuery(document).ready(function($) {
    var cfTable = $('#cfTable').DataTable({
        serverSide: true,
        searchDelay: 800, // Built-in debounce delay in milliseconds
        ajax: {
            url: cf_admin_ajax.ajax_url,
            type: 'POST',
            data: function(d) {
                d.action = 'cf_get_entries';
                d.nonce = cf_admin_ajax.nonce;
            }
        },
        columns: [
            {
                className: 'details-control',
                orderable: false,
                data: null,
                defaultContent: '+'
            },
            { data: 1 }, // ID
            { data: 2 }, // Name
            { data: 3 }, // Email
            { data: 4 }, // Phone
            { data: 5 }, // Created At
            { data: 6, orderable: false } // Actions
        ],
        order: [[1, 'desc']], // Default order by ID desc
        language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Loading...'
        }
    });

    // Move DataTable controls to search section
    setTimeout(function() {
        var filterDiv = $('#cfTable_filter');
        var lengthDiv = $('#cfTable_length');
        
        if (filterDiv.length && lengthDiv.length) {
            var searchSection = $('.cf-search-section');
            if (searchSection.length) {
                // Create a wrapper for controls
                var controlsHtml = '<div class="cf-controls-wrapper">' +
                    '<div class="cf-length-control">' + lengthDiv.html() + '</div>' +
                    '<div class="cf-filter-control">' + filterDiv.html() + '</div>' +
                    '</div>';
                
                searchSection.html(controlsHtml);
                
                // Re-attach event handler to the moved search input
                searchSection.find('input[type="search"]').on('keyup', function() {
                    clearTimeout(searchTimeout);
                    var currentValue = $(this).val();
                    
                    if (currentValue !== lastSearchValue) {
                        lastSearchValue = currentValue;
                        searchTimeout = setTimeout(function() {
                            cfTable.search(currentValue).draw();
                        }, 700);
                    }
                });
                
                // Hide original controls
                filterDiv.hide();
                lengthDiv.hide();
            }
        }
    }, 100);

    // Debounce search - additional protection against rapid requests
    var searchTimeout;
    var lastSearchValue = '';
    $('#cfTable_filter input').on('keyup', function() {
        clearTimeout(searchTimeout);
        var currentValue = $(this).val();
        
        // Only trigger if value actually changed
        if (currentValue !== lastSearchValue) {
            lastSearchValue = currentValue;
            searchTimeout = setTimeout(function() {
                cfTable.search(currentValue).draw();
            }, 700); // Local debounce (searchDelay handles server-side)
        }
    });

    // Expand row details
    $('#cfTable tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = cfTable.row(tr);
        var cell = $(this);

        if (row.child.isShown()) {
            row.child.hide();
            cell.text('+');
        } else {
            var id = row.data()[1]; // ID is in column 1

            $.ajax({
                url: cf_admin_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'cf_get_entry_details',
                    nonce: cf_admin_ajax.nonce,
                    id: id
                },
                beforeSend: function() {
                    cell.html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    cell.text('-');
                    if (response.success) {
                        var details = '<div><strong>Message:</strong> ' + response.data.message + '</div>';
                        if (response.data.file_url) {
                            details += '<div><strong>File:</strong> <a href="' + response.data.file_url + '" target="_blank">Download</a></div>';
                        }
                        row.child(details).show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data
                        });
                    }
                },
                error: function() {
                    cell.text('+');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load details'
                    });
                }
            });
        }
    });

    // Delete single entry
    $('#cfTable tbody').on('click', '.cf-delete-btn', function() {
        var $btn = $(this);
        var id = $btn.data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the entry.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: cf_admin_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'cf_delete_entry',
                        nonce: cf_admin_ajax.nonce,
                        id: id
                    },
                    beforeSend: function() {
                        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {
                        if (response.success) {
                            cfTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: response.data
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.data
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete entry'
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('<i class="fa fa-trash"></i>');
                    }
                });
            }
        });
    });

    // Export to CSV
    $('#cf-export-csv').on('click', function() {
        var $btn = $(this);
        var searchValue = cfTable.search();

        $.ajax({
            url: cf_admin_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'cf_export_csv',
                nonce: cf_admin_ajax.nonce,
                search: searchValue
            },
            beforeSend: function() {
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Exporting...');
            },
            success: function(response) {
                if (response.success) {
                    // Create and download CSV
                    var blob = new Blob([response.data.csv], { type: 'text/csv;charset=utf-8;' });
                    var link = document.createElement('a');
                    if (link.download !== undefined) {
                        var url = URL.createObjectURL(blob);
                        link.setAttribute('href', url);
                        link.setAttribute('download', 'custom-form-entries-' + new Date().toISOString().split('T')[0] + '.csv');
                        link.style.visibility = 'hidden';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.data
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to export data'
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html('Export to CSV');
            }
        });
    });

    // Delete all entries
    $('#cf-delete-all').on('click', function() {
        var $btn = $(this);
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete all entries.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete All'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: cf_admin_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'cf_delete_all_entries',
                        nonce: cf_admin_ajax.nonce
                    },
                    beforeSend: function() {
                        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
                    },
                    success: function(response) {
                        if (response.success) {
                            cfTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: response.data
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.data
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete all entries'
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('Delete All Entries');
                    }
                });
            }
        });
    });
});