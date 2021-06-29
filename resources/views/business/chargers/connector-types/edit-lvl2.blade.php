@extends('business.layouts.master')

<div class="row">
    <div class="col offset-s2 s10">
        <div class="card">
            <div class="card-content">
                <table class="striped bpg-arial">
                    <thead>
                        <tr style="color: black">
                            <th>@lang('business.chargers.connector-type')</th>
                            <th>@lang('business.chargers.start-time')</th>
                            <th>@lang('business.chargers.end-time')</th>
                            <th>@lang('business.chargers.min-kwt')</th>
                            <th>@lang('business.chargers.max-kwt')</th>
                            <th>@lang('business.chargers.price')</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <form
                                action="{{ route('charging-prices.update', $chargingPrice->id) }}"
                                class="set-lvl2-charging-price"
                                method="POST">
                                @csrf
                                @method('put')
                                <td>
                                    <div class="input-field">
                                        {{ $chargingPrice -> getConnector() }} 
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <select name="start_time" required class="browser-default">
                                            @foreach ($dayTimesRange as $time)
                                                <option @if($chargingPrice->start_time === $time) selected @endif> {{ $time }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <select name="end_time" required class="browser-default">
                                            @foreach ($dayTimesRange as $time)
                                                <option @if($chargingPrice->end_time === $time) selected @endif>
                                                    {{ $time }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <input 
                                            type="number" 
                                            id="min_kwt" 
                                            name="min_kwt" 
                                            step="0.01" 
                                            value="{{ $chargingPrice->min_kwt }}"
                                            required
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <input 
                                            type="number" 
                                            id="max_kwt" 
                                            name="max_kwt" 
                                            step="0.01" 
                                            value="{{ $chargingPrice->max_kwt }}"
                                            required
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field">
                                        <input 
                                            type="number" 
                                            id="price" 
                                            name="price" 
                                            step="0.01" 
                                            value="{{ $chargingPrice->price }}"
                                        />
                                    </div>
                                </td>
                                <td class="right">
                                    <div class="input-field">
                                        <button type="submit" class="btn waves-effect waves-light btn-small green">
                                            <i class="material-icons">check</i>
                                        </button>
                                    </div>
                                </td>
                            </form>
                        </tr>
                    </tbody>
                </table>
                @error('price')
                    <span style="color: red">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>
