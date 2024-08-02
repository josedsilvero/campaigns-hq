@extends('layouts.app')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        @session('success')
        <div class="alert alert-success" role="alert">
            {{ $value }}
        </div>
        @endsession

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Note
                </div>
                <div class="float-end">
                    <a href="{{ route('campaign_notes.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('campaign_notes.update', $campaignNote->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="campaign_id" class="col-md-4 col-form-label text-md-end text-start">campaign_id</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('campaign_id') is-invalid @enderror" id="campaign_id" name="campaign_id" value="{{ $campaignNote->campaign_id }}">
                            @error('campaign_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">CPA</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('cpa') is-invalid @enderror" id="cpa" name="cpa" value="{{ $campaignNote->cpa }}">
                            @error('cpa')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="budget" class="col-md-4 col-form-label text-md-end text-start">Budget</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control @error('budget') is-invalid @enderror" id="budget" name="budget" value="{{ $campaignNote->budget }}">
                            @error('budget')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="observation" class="col-md-4 col-form-label text-md-end text-start">observation</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('observation') is-invalid @enderror" id="observation" name="observation">{{ $campaignNote->observation }}</textarea>
                            @error('observation')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection