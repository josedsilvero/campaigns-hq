@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<style>
    .buttonContainer {
        float: right;
        width: 15px;
        margin-left: 10px;
    }

    .myButton {
        background-color: rgb(13, 110, 253);
        border-bottom-color: rgb(13, 110, 253);
        color: rgb(255, 255, 255);
        float: right;

    }

    .campaignName {
        color: rgb(56, 88, 152);
    }
</style>

<div class=" row justify-content-center mt-3">
    <div class="col-md-12">

        @session('success')
        <div class="alert alert-success" role="alert">
            {{ $value }}
        </div>
        @endsession
        <?php
        $dateTime = new DateTime($latestRecord);
        $formattedDate = $dateTime->format('F d, Y h:i A');
        echo 'Latest update: ', $formattedDate;
        ?>
        <div class="card">
            <div class="card-header">Lista de Campañas</div>
            <div class="row pb-3" style="padding-top: 0.5em; padding-left: 1.5em;">
                <div class="col-md-3">
                    <label>Min date:</label>
                    <input type="date" id="min" name="min" value="<?php echo date("Y-m-d", strtotime("yesterday")); ?>" placeholder="dd-mm-yyyy" class="date-range-filter"></>
                </div>
                <div class="col-md-3">
                    <label>Max date:</label>
                    <input type="date" id="max" name="max" value="<?php echo date("Y-m-d", strtotime("yesterday")); ?>" placeholder="dd-mm-yyyy" class="date-range-filter"></>
                </div>
                <div class="col-md-3">
                    <button id="export" class="btn btn-success"><i class="bi bi-file-excel"></i>Export</button>
                </div>
            </div>
            <div class="card-body overflow-scroll">
                <table class="table table-striped table-bordered" id="campaigns">
                    <thead>
                        <tr>
                            <th scope="col">C#</th>
                            <th scope="col">Name</th>
                            <th scope="col">CPA FB</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Conv.</th>
                            <th scope="col">Gross Rev</th>
                            <th scope="col">Net Rev</th>
                            <th scope="col">% Net Rev</th>
                            <th scope="col">eCPA</th>
                            <th scope="col">Searches</th>
                            <th scope="col">Clicks Rev</th>
                            <th scope="col">Conv valid.</th>
                            <th scope="col">CTR SERP</th>
                            <th scope="col">Avg RPC</th>
                            <th scope="col">Budget</th>
                            <th scope="col">Obs</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <!-- Add Modal -->
                    <div class=" modal fade" id="obsModal-add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Campaign Note</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('campaign_notes.store') }}" method="post" id=edit-cpa-form>
                                        @csrf

                                        <div class="mb-3 row">
                                            <div class="col-md-6">
                                                <input type="hidden" class="form-control @error('campaign_id') is-invalid @enderror" name="campaign_id" id="campaign-id-input-3">
                                                @error('campaign_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label for="observation" class="col-md-4 col-form-label text-md-end text-start">Observation</label>
                                            <div class="col-md-6">
                                                <textarea class="form-control @error('observation') is-invalid @enderror" id="observation" name="observation" autofocus>{{ old('observation') }}</textarea>
                                                @error('observation')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" value="Update">Guardar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <tbody>
                        <tr>
                            @forelse ($campaigns as $campaign)
                            @if (!is_null($campaign->note_id))
                            <!-- Edit Modal -->
                            <div class="modal fade" id="obsModal-edit-{{$campaign->note_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Campaign Note</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('campaign_notes.update', $campaign->note_id) }}" method="post">
                                                @csrf
                                                @method("PUT")

                                                <div class="mb-3 row">
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control @error('campaign_id') is-invalid @enderror" name="campaign_id" id="edit-campaign-id-input" value="{{ $campaign->facebook_id }}">
                                                        @error('campaign_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @if(!is_null($campaign->cpa))
                                                <div class="mb-3 row">
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control @error('cpa') is-invalid @enderror" name="cpa" id="edit-cpa-input" value="{{ $campaign->cpa }}">
                                                        @error('cpa')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="mb-3 row">
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control @error('budget') is-invalid @enderror" autocomplete="off" id="edit-budget-input" name="budget" value="{{ $campaign->budget }}" autofocus>
                                                        @error('budget')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label for="observation" class="col-md-4 col-form-label text-md-end text-start">Observation</label>
                                                    <div class="col-md-6">
                                                        <textarea class="form-control @error('observation') is-invalid @enderror" id="observation" name="observation" autofocus>{{ $campaign->observation }}</textarea>
                                                        @error('observation')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary" value="Update">Guardar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <span class="campaignName">
                                    {{ $campaign->campaign_name }}
                                </span>
                            </td>
                            <td>
                                {{$campaign->cpa}}
                            </td>
                            <td>{{ $campaign->spend }}</td>
                            <td>{{ $campaign->purchase_value }}</td>
                            <td>{{ $campaign->revenue }}</td>
                            @if($campaign->net_revenue<0) <td style="color: red">{{$campaign->net_revenue }}</td>
                                <td style="color: red">{{ $campaign->net_revenue_perc }}%</td>
                                @elseif($campaign->net_revenue_perc>=50 && $campaign->net_revenue>10)
                                <td style="background-color: yellow">{{$campaign->net_revenue }}</td>
                                @if($campaign->net_revenue_perc>=150)
                                <td style="background-color: lime">{{ $campaign->net_revenue_perc }}%</td>
                                @else
                                <td style="background-color: yellow">{{ $campaign->net_revenue_perc }}%</td>
                                @endif
                                @else
                                <td>{{ $campaign->net_revenue }}</td>
                                @if($campaign->net_revenue_perc>150)
                                <td style="background-color: lime">{{ $campaign->net_revenue_perc }}%</td>
                                @else
                                <td>{{ $campaign->net_revenue_perc }}%</td>
                                @endif
                                @endif()
                                <td>{{ $campaign->ecpa }}</td>
                                <td>{{ $campaign->lander_visitors }}</td>
                                <td>{{ $campaign->revenue_events }}</td>
                                <td>{{ $campaign->conv_valid }}%</td>
                                <td>{{ $campaign->ctr_serp }}%</td>
                                <td>{{ $campaign->avg_rpc }}</td>
                                <td>
                                    {{$campaign->budget}}
                                </td>
                                <td>
                                    <div style=" display:inline-block">
                                        @if (is_null($campaign->note_id))
                                        <span></span>
                                        <div class="buttonContainer">
                                            <button type=" button" class="myButton edit-cpa-button" data-bs-toggle="modal" href="#obsModal-add" data-record-id='{{$campaign->facebook_id}}'>
                                                <i class="bi bi-pencil-square" style="font-size: small;"></i></button>
                                        </div>
                                        @else
                                        {{ $campaign->observation }}
                                        <div class="buttonContainer">
                                            <button type="button" class="myButton update-cpa-button" data-bs-toggle="modal" href="#obsModal-edit-{{$campaign->note_id}}">
                                                <i class="bi bi-pencil-square" style="font-size: small;"></i></button>
                                        </div>
                                        @endif
                                </td>
                                <td>{{ $campaign->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <td colspan="6">
                            <span class="text-danger">
                                <strong>No se encontraron campañas</strong>
                            </span>
                        </td>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align:right">Total Cost:</th>
                            <th></th>
                            <th colspan="2" style="text-align:right">Total Profit:</th>
                            <th></th>
                            <th colspan="2" style="text-align:right">Total %Net:</th>
                            <th></th>
                            <th colspan="2" style="text-align:right">Profit Chena:</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

                {{ $campaigns->links() }}

            </div>
        </div>
    </div>
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
    let minDate, maxDate;

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
        table = $('#campaigns').DataTable({
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
                    .column(3)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                totalProfit = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);

                // Total over this page
                pageTotalCost = api
                    .column(3, {
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
                api.column(3).footer().innerHTML =
                    '$' + pageTotalCost + ' ($' + totalCost + ' total)';
                api.column(6).footer().innerHTML =
                    '$' + pageTotalProfit + ' ($' + totalProfit + ' total)';
                api.column(9).footer().innerHTML =
                    pageTotalNetPerc + '% (' + totalNetPerc + '% total)';
                api.column(12).footer().innerHTML =
                    '$' + pageProfitChena + ' ($' + totalProfitChena + ' total)';
            },
            'pageLength': 100,
            'buttons': [{
                extend: 'excel',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            }]
        });

        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });

        // Re-draw the table when the a date range filter changes
        $('.date-range-filter').change(function() {
            var min = $('#min').val();
            minDate = stringToDate(min, "yyyy-mm-dd", "-");
            var max = $('#max').val();
            maxDate = stringToDate(max, "yyyy-mm-dd", "-");
            table.draw();
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


    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#min').val();
            minDate = stringToDate(min, "yyyy-mm-dd", "-");
            var max = $('#max').val();
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


    $(document).on('click', '.edit-cpa-button', function() {
        $('#campaign-id-input').val($(this).data('record-id'));
        $('#campaign-id-input-2').val($(this).data('record-id'));
        $('#campaign-id-input-3').val($(this).data('record-id'));
    });
</script>