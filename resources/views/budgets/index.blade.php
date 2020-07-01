@extends('layout')

@section('title', 'Budgets')

@section('body')
    <div class="wrapper my-3">
        <h2>Budgets</h2>
        <div class="box mt-3">
            @foreach ($budgets as $budget)
                <div class="box__section row">
                    <div class="row__column row__column--compact row__column--middle mr-2">
                        <div style="width: 15px; height: 15px; border-radius: 2px; background: #{{ $budget->tag->color }};"></div>
                    </div>
                    <div class="row__column">
                        <div>{{ $budget->tag->name }}</div>
                        <div class="mt-1" style="font-size: 14px; font-weight: 600;">{!! $currency !!} {{ $budget->amount }} per {{ 'month' }}</div>
                    </div>
                    <div class="row__column">
                        <progress value="{{ $budget->spent }}" min="0" max="{{ $budget->amount }}" style="width: 300px; height: 20px;"></progress>
                        <div class="mt-1" style="font-size: 14px; font-weight: 600;">Spent {!! $currency !!} {{ $budget->spent }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
