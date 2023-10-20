$(document).ready(function () {
    new DataTable('#transactions', {"order": [[0, "desc"]], "iDisplayLength": -1, paging: false, "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], "columnDefs": [{"width": "20%", "targets": 0}],
    });
});