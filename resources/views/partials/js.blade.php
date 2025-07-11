<script>
    // Sidebar toggle for mobile
    const openSidebarBtn = document.getElementById('open-sidebar');
    const closeSidebarBtn = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('sidebar');

    openSidebarBtn.addEventListener('click', () => {
        sidebar.classList.add('open');
    });

    closeSidebarBtn.addEventListener('click', () => {
        sidebar.classList.remove('open');
    });

    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !openSidebarBtn.contains(e.target) && sidebar.classList.contains(
                'open')) {
            sidebar.classList.remove('open');
        }
    });

    // Bar Chart: Attendance Status
    const attendanceStatusChart = new Chart(document.getElementById('attendanceStatusChart'), {
        type: 'bar',
        data: {
            labels: ['Present', 'Absent', 'Leave'],
            datasets: [{
                label: 'Number of Employees',
                data: [1, 1, 1], // Dummy data
                backgroundColor: ['#134a6b', '#0f3a54', '#1a5d85'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Employees'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Status'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Pie Chart: Department Distribution
    const departmentChart = new Chart(document.getElementById('departmentChart'), {
        type: 'pie',
        data: {
            labels: ['IT', 'HR', 'Finance'],
            datasets: [{
                label: 'Department Distribution',
                data: [1, 1, 1], // Dummy data
                backgroundColor: ['#134a6b', '#0f3a54', '#1a5d85'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Download PDF
    document.getElementById('download-pdf').addEventListener('click', () => {
        const element = document.getElementById('report-table');
        const opt = {
            margin: 0.5,
            filename: 'attendance_report.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        };
        html2pdf().set(opt).from(element).save();
    });

    // Download CSV
    document.getElementById('download-csv').addEventListener('click', () => {
        const rows = [
            ['Employee Name', 'Department', 'Status', 'Notes', 'Date'],
            ['John Doe', 'IT', 'Present', 'On time', '2025-05-16'],
            ['Jane Smith', 'HR', 'Absent', 'Sick leave', '2025-05-16'],
            ['Mike Johnson', 'Finance', 'Leave', 'Vacation', '2025-05-16']
        ];
        const csv = Papa.unparse(rows);
        const blob = new Blob([csv], {
            type: 'text/csv;charset=utf-8;'
        });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'attendance_report.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>


<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: "<'row mb-3'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                    extend: 'copy',
                    className: 'btn btn-primary mt-2 ml-2'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-success mt-2 ml-2'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger mt-2 ml-2'
                },
                {
                    extend: 'print',
                    className: 'btn btn-info mt-2 ml-2'
                }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records..."
            }
        });

        // Optional: Add Bootstrap classes to search & pagination after table init
        $('.dataTables_filter input').addClass('form-control ms-2').css('width', '250px');
        $('.dataTables_filter input').addClass('form-control ms-2').css('margin-top', '10px');
        $('.dataTables_length select').addClass('form-select ms-2');
        $('.dataTables_paginate').addClass('pagination justify-content-end');
        $('.dataTables_paginate a').addClass('page-link');
    });
</script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.focus();
            // Move cursor to the end of the text
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    });
</script>
