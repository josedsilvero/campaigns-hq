@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<div class="card-body overflow-scroll">
    <div style="text-align: right;">
        <a href="{{ route('export') }}" class="btn btn-success">Export CSV</a>
        <button type=" button" class="btn btn-primary" data-bs-toggle="modal" href="#importModal">
            Import CSV
        </button>
        <!-- Add Modal -->
        <div class=" modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-tiktok"></i>TikTok Campaigns</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @include('tiktok_campaign.csvimport')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h2><i class="bi bi-tiktok"></i>TikTok Campaigns</h2>
    <table class="table table-striped table-bordered" id="tiktok_campaigns">
        <thead>
            <tr>
                <th scope="col">C#</th>
                <th>Campaign Id</th>
                <th>Campaign Name</th>
                <th>Primary Status</th>
                <th>Account Id</th>
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
                <td>{{ $list->campaign_id }}</td>
                <td>{{ $list->campaign_name }}</td>
                <td>{{ $list->primary_status }}</td>
                <td>{{ $list->account_id }}</td>
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
    </table>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        table = $('#tiktok_campaigns').DataTable({
            "paging": false,
        });
    });
</script>