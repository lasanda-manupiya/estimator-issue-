@if($project->formulas->isNotEmpty())
    @foreach($project->formulas as $formula)
        @php 
            $str = $formula->formula;
            $pt1 = "/x/i";
            $str = preg_replace($pt1, "*", $str);
            $pt2 = "/([a-z])+/i";
            $str = preg_replace($pt2, "\$$0", $str);
            $pt3 = "/([0-9])+%/";
            $str = preg_replace($pt3, "($0/100)", $str);
            $pt4 = "/%/";
            $str = preg_replace($pt4, "", $str);
            $rate = $project->hr_rate;


            $e = "\$comm = $str;";
            eval($e);  
            $formula_value = $comm;
        @endphp
        <tr class="formula-row-{{ $formula->id }}">
            <td>
                <input type="text" name="formulas[{{ $formula->id }}][keyword]" value="{{ $formula->keyword }}"  class="form-control" onchange="update_formula_row('{{ route('estimates.ajax.update.project.formula.row', $formula->id) }}', 'keyword', this.value);">
            </td>
            <td>
                <input type="text" name="formulas[{{ $formula->id }}][description]" value="{{ $formula->description }}" class="form-control" onchange="update_formula_row('{{ route('estimates.ajax.update.project.formula.row', $formula->id) }}', 'description', this.value);">
            </td>
            <td>
                <input type="text" name="formulas[{{ $formula->id }}][formula]" value="{{ $formula->formula }}" class="form-control" onchange="update_formula_row('{{ route('estimates.ajax.update.project.formula.row', $formula->id) }}', 'formula', this.value);">
            </td>
            <td>
                <input type="text" name="formulas[{{ $formula->id }}][value]" value="{{ number_format((float)$formula_value, 2, '.', '') }}" class="form-control" onkeypress="javascript:return isNumber(event)" readonly>
            </td>
            <td>
                <a title="Delete formula" href="javascript:;" class="btn btn-sm btn-danger remove-formula-row" data-formula-id="{{ $formula->id }}" data-route="{{ route('estimates.ajax.remove.project.formula.row', $formula->id) }}">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
