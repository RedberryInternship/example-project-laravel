@extends('business.layouts.master')

    <div class="row">
        <div class="col offset-s3 s9">
            <div class="card">
                <div class="card-content">
                    <table class="striped bpg-arial">
                        <thead>
                            <tr style="color: black">
                                <th>@lang('business.chargers.connector-type')</th>
                                <th>@lang('business.chargers.minutes-from')</th>
                                <th>@lang('business.chargers.minutes-to')</th>
                                <th>@lang('business.chargers.price')</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <form
                                        action="{{ route('fast-charging-prices.update', $fastChargingPrice -> id) }}"
                                        class="set-fast-charging-price"
                                        method="POST">
                                    @csrf
                                    @method('put')

                                    <td>
                                        <div class="input-field">
                                            <input type="text" disabled value="{{ $fastChargingPrice->connectorType() }} ">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <input 
                                                type="number" 
                                                id="start_minutes" 
                                                name="start_minutes" 
                                                value="{{ $fastChargingPrice -> start_minutes }}"
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <input 
                                                type="number" 
                                                id="end_minutes" 
                                                name="end_minutes"
                                                value="{{ $fastChargingPrice -> end_minutes }}"
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                id="price" 
                                                name="price"
                                                value="{{ $fastChargingPrice -> price }}"
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-field">
                                            <button type="submit" class="btn waves-effect waves-light btn-small green">
                                                <i class="material-icons">check</i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="right"></td>
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
