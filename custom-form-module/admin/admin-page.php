<?php
/**
 * Admin Page for Custom Form Module
 *
 * @package CustomFormModule
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin page callback
 */
function cf_admin_page_callback() {
    ?>
    <div class="wrap">
        <h1>Custom Forms</h1>
        <div style="margin-bottom: 20px;">
            <button id="cf-export-csv" class="button button-primary" style="background-color: #28a745; border-color: #28a745;">Export to CSV</button>
            <button id="cf-delete-all" class="button button-secondary" style="background-color: #dc3545; border-color: #dc3545; color: white;">Delete All Entries</button>
        </div>
        <div class="cf-search-section">
            <!-- This will be populated by DataTables -->
        </div>
        <table id="cfTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <style>
        .cf-search-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .cf-controls-wrapper {
            display: flex;
            gap: 50%;
            align-items: center;
            flex-wrap: wrap;
        }
        .cf-length-control {
            flex: 0 0 auto;
        }
        .cf-length-control label {
            margin-right: 8px;
        }
        .cf-length-control select {
            min-width: 80px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .cf-filter-control {
            flex: 1 1 auto;
            min-width: 200px;
        }
        .cf-filter-control label {
            margin-right: 8px;
        }
        .cf-filter-control input[type="search"] {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }
        #cfTable_wrapper {
            margin-top: 0;
        }
        #cfTable_filter {
            display: none;
        }
        #cfTable_length {
            display: none;
        }
        #cfTable {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
        #cfTable th, #cfTable td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        #cfTable thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }
        #cfTable tbody tr:hover {
            background-color: #f5f5f5;
        }
        .cf-delete-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
        }
        .cf-delete-btn:hover {
            color: #c82333;
        }
        .cf-file-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .cf-download-btn {
            color: #007cba;
        }
        .cf-download-btn:hover {
            color: #005a87;
            background-color: #f0f6fc;
        }
        .cf-open-btn {
            color: #28a745;
        }
        .cf-open-btn:hover {
            color: #218838;
            background-color: #f0fdf4;
        }
        .details-control {
            cursor: pointer;
            font-weight: bold;
        }
        .dataTables_processing {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            z-index: 9999;
            text-align: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            font-size: 16px;
        }
        .dataTables_processing i {
            margin-right: 10px;
            font-size: 24px;
        }
    </style>
    <?php
}