@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<style>
    .campaignName {
        color: rgb(56, 88, 152);
    }
</style>

<div class="card-body overflow-scroll">
    <div style="text-align: right;">
        <button id="export" class="btn btn-success"><i class="bi bi-file-excel"></i>Export</button>
    </div>
    <h2><i class="bi bi-tiktok"></i>TikTok Campaigns</h2>
    <table class="table table-striped table-bordered" id="tiktok_campaigns">
        <thead>
            <tr>
                <th scope="col">C#</th>
                <th>Campaign Name</th>
                <th>Primary Status</th>
                <th>Conversions</th>
                <th>Cost per Conversion</th>
                <th>Cost</th>
                <th>CTR</th>
                <th>Clicks</th>
                <th>CPC</th>
                <th>Frequency</th>
                <th>Currency</th>
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
                <td>{{ $list->primary_status }}</td>
                <td>{{ $list->conversions }}</td>
                <td>{{ $list->cost_per_conversion }}</td>
                <td>
                    {{$list->cost}}
                </td>
                <td>{{ $list->ctr }}</td>
                <td>{{ $list->clicks }}</td>
                <td>{{ $list->cpc }}</td>
                <td>{{ $list->frequency }}</td>
                <td>{{ $list->currency }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total Conv.:</th>
                <th></th>
                <th colspan="1" style="text-align:right">Total Cost:</th>
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
                    .column(5)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                totalProfit = api
                    .column(3)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Total over this page
                pageTotalCost = api
                    .column(5, {
                        page: 'current'
                    })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                pageTotalProfit = api
                    .column(3, {
                        page: 'current'
                    })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                pageTotalCost = pageTotalCost.toFixed(2);
                totalCost = totalCost.toFixed(2);
                pageTotalProfit = pageTotalProfit.toFixed(2);
                totalProfit = totalProfit.toFixed(2);

                // Update footer
                api.column(5).footer().innerHTML =
                    '$' + pageTotalCost + ' ($' + totalCost + ' total)';
                api.column(3).footer().innerHTML =
                    '$' + pageTotalProfit + ' ($' + totalProfit + ' total)';
            },
            'pageLength': 100
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
    });
</script>