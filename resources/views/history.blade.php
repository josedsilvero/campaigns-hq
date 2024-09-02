@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<style>
    .campaignName {
        color: rgb(56, 88, 152);
    }

    .parent {
        padding: 1rem;
        display: inline-block;
    }

    .float-left-child {
        float: left;
    }
</style>

<div class="card-body overflow-scroll">
    <div style="text-align: right;">
        <button id="export" class="btn btn-success"><i class="bi bi-file-excel"></i>Export</button>
    </div>
    <h2><i class="bi bi-clock-history"></i>Historial</h2>
    <div class='parent'>
        <div class='child float-left-child'>
            <button id="left" class="btn btn-secondary"><i class="bi bi-arrow-left"></i></button>
        </div>
        <div class='child float-left-child'>
            <input type="date" id="min" name="min" value="<?php echo date("Y-m-d", strtotime("yesterday")); ?>" placeholder="dd-mm-yyyy" class="date-range-filter"></>
        </div>
        <div class='child float-left-child'>
            <button id="right" class="btn btn-secondary"><i class="bi bi-arrow-right"></i></button>
        </div>
    </div>
    <table class="table table-striped table-bordered" id="tiktok_campaigns">
        <thead>
            <tr>
                <th>C#</th>
                <th>Campaign Name</th>
                <th>CPA FB</th>
                <th>Budget</th>
                <th>Cost</th>
                <th>Conv.</th>
                <th>Gross Rev.</th>
                <th>Net Rev</th>
                <th>% Net Rev</th>
                <th>eCPA</th>
                <th>Searches</th>
                <th>Clicks Rev</th>
                <th>Conv valid.</th>
                <th>CTR SERP</th>
                <th>Avg RPC</th>
                <th>Obs</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>


            @foreach ($campaigns as $list)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>
                    <span class="campaignName">
                        {{ $list->campaign_name }}
                    </span>
                </td>
                <td>{{ $list->cpa }}</td>
                <td>{{ $list->budget }}</td>
                <td>{{ $list->spend }}</td>
                <td>{{ $list->purchase_value }}</td>
                <td>{{ $list->revenue }}</td>
                @if($list->net_revenue<0) <td style="color: red">{{$list->net_revenue }}</td>
                    <td style="color: red">{{ $list->net_revenue_perc }}%</td>
                    @elseif($list->net_revenue_perc>=50 && $list->net_revenue>10)
                    <td style="background-color: yellow">{{$list->net_revenue }}</td>
                    @if($list->net_revenue_perc>=150)
                    <td style="background-color: lime">{{ $list->net_revenue_perc }}%</td>
                    @else
                    <td style="background-color: yellow">{{ $list->net_revenue_perc }}%</td>
                    @endif
                    @else
                    <td>{{ $list->net_revenue }}</td>
                    @if($list->net_revenue_perc>150)
                    <td style="background-color: lime">{{ $list->net_revenue_perc }}%</td>
                    @else
                    <td>{{ $list->net_revenue_perc }}%</td>
                    @endif
                    @endif()
                    <td>{{ $list->ecpa }}</td>
                    <td>{{ $list->lander_visitors }}</td>
                    <td>{{ $list->revenue_events }}</td>
                    <td>{{ $list->conv_valid }}</td>
                    <td>{{ $list->ctr_serp }}</td>
                    <td>{{ $list->avg_rpc }}</td>
                    <td>{{ $list->observation }}</td>
                    <td>{{ $list->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align:right">Total Cost:</th>
                <th></th>
                <th colspan="1" style="text-align:right">Total Profit:</th>
                <th></th>
                <th colspan="2" style="text-align:right">Total %Net:</th>
                <th></th>
                <th colspan="2" style="text-align:right">Profit Chena:</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script>
    function stringToDate(_date, _format, _delimiter) {
        var formatLowerCase = _format.toLowerCase();
        var formatItems = formatLowerCase.split(_delimiter);
        var dateItems = _date.split(_delimiter);
        var monthIndex = formatItems.indexOf("mm");
        var dayIndex = formatItems.indexOf("dd");
        var yearIndex = formatItems.indexOf("yyyy");
        var month = parseInt(dateItems[monthIndex]);
        month -= 1;
        var formatedDate = new Date(dateItems[yearIndex], month, dateItems[dayIndex]);
        return formatedDate;
    }

    $(document).ready(function() {
        table = $('#tiktok_campaigns').DataTable({
            'footerCallback': function(row, data, start, end, display) {
                let api = this.api();

                // Remove the formatting to get integer data for summation
                let intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i :
                        0;
                };

                // Total over all pages
                totalCost = api
                    .column(4)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                totalProfit = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Total over this page
                pageTotalCost = api
                    .column(4, {
                        page: 'current'
                    })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                pageTotalProfit = api
                    .column(6, {
                        page: 'current'
                    })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                pageTotalCost = pageTotalCost.toFixed(2);
                totalCost = totalCost.toFixed(2);
                pageTotalProfit = pageTotalProfit.toFixed(2);
                totalProfit = totalProfit.toFixed(2);
                pageProfitChena = (pageTotalProfit / 2).toFixed(2);
                totalProfitChena = (totalProfit / 2).toFixed(2);
                pageTotalNetPerc = ((pageTotalProfit / pageTotalCost) * 100).toFixed(2);
                totalNetPerc = ((totalProfit / totalCost) * 100).toFixed(2);

                // Update footer
                api.column(2).footer().innerHTML =
                    '$' + pageTotalCost + ' ($' + totalCost + ' total)';
                api.column(4).footer().innerHTML =
                    '$' + pageTotalProfit + ' ($' + totalProfit + ' total)';
                api.column(7).footer().innerHTML =
                    pageTotalNetPerc + '% (' + totalNetPerc + '% total)';
                api.column(10).footer().innerHTML =
                    '$' + pageProfitChena + ' ($' + totalProfitChena + ' total)';
            },
            paging: false
        });

        $("#export").click(function() {
            let table = document.getElementsByTagName("table");
            let currentDate = new Date().toLocaleDateString();
            let dateStamp = currentDate.replaceAll('/', '-');
            let docName = 'CampaignsReport' + dateStamp + '.xlsx';
            TableToExcel.convert(table[0], {
                name: docName,
                sheet: {
                    name: `CampaignsReport`
                }
            });

        });

        $('.date-range-filter').change(function() {
            var min = $('#min').val();
            minDate = stringToDate(min, "yyyy-mm-dd", "-");
            maxDate = stringToDate(min, "yyyy-mm-dd", "-");
            table.draw();
        });

        $('#left').click(function() {
            var date = $('#min').val();
            minDate = stringToDate(date, "yyyy-mm-dd", "-");
            minDate.setDate(minDate.getDate() - 1);
            var formattedDate = minDate.toISOString().substring(0, 10);
            $('#min').val(formattedDate);
            table.draw();
        });

        $('#right').click(function() {
            var date = $('#min').val();
            minDate = stringToDate(date, "yyyy-mm-dd", "-");
            minDate.setDate(minDate.getDate() + 1);
            var formattedDate = minDate.toISOString().substring(0, 10);
            $('#min').val(formattedDate);
            table.draw();
        });
    });

    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#min').val();
            minDate = stringToDate(min, "yyyy-mm-dd", "-");
            var max = $('#min').val();
            maxDate = stringToDate(max, "yyyy-mm-dd", "-");
            var date = stringToDate(data[16], "dd/MM/yyyy", "/") || 0; // Our date column in the table
            if (
                (minDate === null && maxDate === null) ||
                (minDate === null && date <= maxDate) ||
                (minDate <= date && maxDate === null) ||
                (minDate <= date && date <= maxDate)
            ) {
                return true;
            }
            return false;
        }
    );
</script>