<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center">Project Total</th>
                    <th class="text-center">Total CO<sub>2</sub></th>
                    <th colspan="2" class="text-center">
                        Labour Total
                        <br>
                        <small class="text-uppercase">{{ $labour_total_formula }}</small>
                    </th>
                    <th colspan="2" class="text-center">
                        Manager Total
                        <br>
                        <small class="text-uppercase">mhr</small>
                    </th>
                    <th colspan="2" class="text-center">
                        Design Total
                        <br>
                        <small class="text-uppercase">dhr</small>
                    </th>
                    <th class="text-center">
                        Material Cost
                        <br>
                        <small class="text-uppercase">nr</small>
                    </th>
                    <th class="text-center">
                        Plant Cost
                        <br>
                        <small class="text-uppercase">nrp</small>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <input type="hidden" name="project_total" id="project_total" readonly="readonly" value="{{ $project_total }}">&pound;{{ number_format((float)$project_total,2) }}
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="totalco_main" id="totalco_main" readonly="readonly" value="{{ $totalco_main }}">&pound;{{ number_format((float)$totalco_main,2) }}
                    </td>
                    <td class="text-center">
                        {{ number_format((float)$labour_total_hour) }} hrs
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="project_hr_total" id="project_hr_total" readonly="readonly" value="{{ $project_hr_total }}">&pound;{{ number_format((float)$labour_total,2) }}
                    </td>
                    <td class="text-center">
                        {{ number_format((float)$manager_total_hour) }} hrs
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="project_mhr_total" id="project_mhr_total" readonly="readonly" value="{{ $project_mhr_total }}"> &pound;{{ number_format((float)$manager_total,2) }}
                    </td>
                    <td class="text-center">
                        {{ number_format((float)$design_total_hour) }} hrs
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="project_dhr_total" id="project_dhr_total" readonly="readonly" value="{{ $project_dhr_total }}"> &pound;{{ number_format((float)$design_total,2) }}
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="project_mhr_total" id="project_mhr_total" readonly="readonly" value="{{ $project_total-(($project_mhr_total)+($project_hr_total)) }}"> &pound;{{ number_format((float)$material_cost,2) }}
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="plant_total" id="plant_total" readonly="readonly" value="{{ $project_total-(($project_mhr_total)+($project_hr_total)) }}"> &pound;{{ number_format((float)$plant_cost,2) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center">Base Margin</th>
                    <th class="text-center">Base Labour</th>
                    @foreach($fq as $key=>$value)
                        <th class="text-center text-uppercase" colspan="2">
                            {{ $key }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="form-group @if($errors->has('base_margin')) has-error @endif">
                            {{ Form::number('base_margin', old('base_margin') ? old('base_margin') : $project->base_margin, [
                                'class' => "form-control",
                                'id' => "base_margin",
                                'onkeypress'=>"javascript:return isNumber(event)",
                                'onkeypress'=>"javascript:return notempty(event)",
                                'min'=>0,
                                'max'=>100
                            ]) }}
                            @if($errors->has('base_margin'))
                                <span class="invalid-feedback">{{ $errors->first('base_margin') }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group @if($errors->has('base_labour')) has-error @endif">
                            {{ Form::text('base_labour', old('base_labour') ? old('base_labour') : $project->labour_value, [
                                'class' => "form-control",
                                'id' => "base_labour",
                                'onkeypress'=>"javascript:return isNumber(event)"
                            ]) }}
                            @if($errors->has('base_labour'))
                                <span class="invalid-feedback">{{ $errors->first('base_labour') }}</span>
                            @endif
                        </div>
                    </td>
                    @foreach($fq as $key => $value)
                        <td class="text-center">{{ $value }}hrs</td>
                        <td class="text-center">&pound;{{ $fk[$key] }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>