$(document).ready(function () {
    new DataTable('#transactions', {"order": [[0, "desc"]], "iDisplayLength": -1, paging: false, "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], "columnDefs": [{"width": "20%", "targets": 0}],
                    "footerCallback": function ( row, data, start, end, display ) {
                                var api = this.api(), data;

                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace('.', '').replace(',', '.').replace(/( .+)/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };

                                // Total over this page
                                pageTotal = api
                                    .column( 1, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );
                                    
                                // Get the currency    
                                var firstRow = api.column( 1, { page: 'current'} ).data()[0];
                                if (firstRow!==undefined)
                                    currency = " "+firstRow.match(/ (.+)/)[1];
                                else
                                    currency = "";
                                
                                // Update table
                                result = 'Amount <small style="font-size: 0.7em">('+pageTotal.toFixed(2)+currency+')';
                                $( api.column( 1 ).header() ).html(result);
                                $( api.column( 1 ).footer() ).html(result);
                            }        
    });
});