@extends('layouts.app')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-12">

        @session('success')
        <div class="alert alert-success" role="alert">
            {{ $value }}
        </div>
        @endsession

        <div class="card">
            <div class="card-header">note List</div>
            <div class="card-body">
                <a href="{{ route('campaign_notes.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New note</a>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">S#</th>
                            <th scope="col">campaign_id</th>
                            <th scope="col">CPA</th>
                            <th scope="col">Budget</th>
                            <th scope="col">Observation</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($campaign_notes as $note)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $note->campaign_id }}</td>
                            <td>{{ $note->cpa }}</td>
                            <td>{{ $note->budget }}</td>
                            <td>{{ $note->observation }}</td>
                            <td>
                                <form action="{{ route('campaign_notes.destroy', $note->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('campaign_notes.show', $note->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                                    <a href="{{ route('campaign_notes.edit', $note->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>

                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this note?');"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <td colspan="6">
                            <span class="text-danger">
                                <strong>No note Found!</strong>
                            </span>
                        </td>
                        @endforelse
                    </tbody>
                </table>

                {{ $campaign_notes->links() }}

            </div>
        </div>
    </div>
</div>

@endsection