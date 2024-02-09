<?php
/*
Plugin Name: Glorious AadiPrinters Support Data
Description: Dashboard widget to show all the support requests for Aadiprinters.
Version: 1.0
Author: GloriousMotive.com
*/

// Helper function to get status color
function get_status_color($status) {
    switch ($status) {
        case 'Open':
            return 'yellow';
        case 'Solved':
            return 'greenyellow'; // Change to greenyellow
        case 'Closed':
            return 'pink'; // Change to pink
        default:
            return 'black';
    }
}

// Add dashboard widget
function add_support_data_dashboard_widget() {
    if (current_user_can('administrator')) {
        wp_add_dashboard_widget(
            'support_data_dashboard_widget',
            'Glorious AadiPrinters Support Log',
            'display_support_data_dashboard_widget'
        );
    }
}
add_action('wp_dashboard_setup', 'add_support_data_dashboard_widget');

// Display dashboard widget content
function display_support_data_dashboard_widget() {
    // Fetch JSON data from GitHub repo
    $json_url = 'https://raw.githubusercontent.com/GloriousMotive/glorious-aadiprinters-support-data/main/data.json';
    $json_data = file_get_contents($json_url);
    $support_data = json_decode($json_data, true);

    // Sort data by date in descending order
    //usort($support_data, function($a, $b) {
        //return strtotime($b['Date']) - strtotime($a['Date']);
    //});

    // Output HTML table
    ?>
    <style>
        /* CSS styles for widget */
        .support-widget {
            font-family: 'Arial', sans-serif;
        }

        .support-widget table {
            width: 100%;
            border-collapse: collapse;
        }

        .support-widget th, .support-widget td {
            border: 1px solid #eaecef;
            padding: 8px;
        }

        .support-widget .status {
            font-weight: bold;
            position: relative; /* Add position relative */
        }
        
        .status .reply {
            cursor: pointer;
            background-color: cyan;
            margin-top: 6px
        }
        
        .status-closed .bttn {
            background-color: pink;
            cursor: default; /* Disable cursor */
            pointer-events: none; /* Disable click events */
            
        }
        
        .status-open .bttn {
            background-color: yellow;
            cursor: default; /* Disable cursor */
            pointer-events: none; /* Disable click events */
        }
        
        .status-solved .bttn {
            background-color: greenyellow;
            cursor: default; /* Disable cursor */
            pointer-events: none; /* Disable click events */
        }

        .support-widget .status button {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            
        }

        .support-widget .status button:hover {
            opacity: 0.8;
        }

        .support-widget .action-buttons {
            margin-top: 20px;
        }

        .support-widget .action-buttons button {
            margin-right: 10px;
            padding: 5px 10px;
            background-color: #0366d6; /* Blue */
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .support-widget .action-buttons button:hover {
            background-color: #0056b3; /* Darker blue */
        }
    </style>

    <div class="support-widget">
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($support_data as $item) : ?>
                    <tr>
                        <td class="status status-<?php echo strtolower($item['Status']); ?>">
                            <button class="bttn">
                                <?php echo $item['Status']; ?>
                            </button><br>
                            <?php if ($item['Status'] == 'Open') : ?>
                                <button class="reply" onclick="window.open('<?php echo $item['Reply_Link']; ?>', '_blank')">Reply</button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo $item['Title']; ?></strong><br>
                            <?php echo $item['Description']; ?>
                        </td>
                        <td>
                            <?php echo $item['Date']; ?><br>
                            <b><?php echo $item['Category']; ?></b>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="action-buttons">
            <button onclick="window.open('https://gloriousmotive.com/support-tickets/', '_blank')">Create Tickets for Support</button>
            <button onclick="window.open('https://gloriousmotive.com/contact', '_blank')">Contact</button>
        </div>
    </div>
    <?php
}
