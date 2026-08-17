<tr class="formula-row-{{ $formula->id }}">
<td>
    <input type="text" name="formulas[{{ $formula->id }}][keyword]" value="{{ $formula->keyword }}"  class="form-control" onchange="update_formula_row('{{ route('estimates.ajax.update.project.formula.row', $formula->id) }}', 'keyword', this.value);">
</td>
<td>
    <input type="text" name="formulas[{{ $formula->id }}][description]" value="{{ $formula->description }}" class="form-control" onchange="update_formula_row('{{ route('estimates.ajax.update.project.formula.row', $formula->id) }}', 'description', this.value);">
</td>
<td>
    <input type="text" name="formulas[{{ $formula->id }}][formula]" value="{{ $formula->formula }}" class="form-control" onchange="update_formula_row('{{ route('estimates.ajax.update.project.formula.row', $formula->id) }}', 'formula', this.value);">
<span id="formula_error_update" style="color:red;display: none" class="error">Invalid Format</span>
</td>
<td>
    <input type="text" name="formulas[{{ $formula->id }}][value]" value="{{ $formula->value }}" class="form-control" onkeypress="javascript:return isNumber(event)" readonly>
</td>
    <td>
        @if(auth()->user()->can('access', 'estimates add'))
            <a title="Delete formula" href="javascript:;" class="btn btn-sm btn-danger remove-formula-row" data-formula-id="{{ $formula->id }}" data-route="{{ route('estimates.ajax.remove.project.formula.row', $formula->id) }}">
                <i class="fas fa-trash"></i>
            </a>
        @endif
    </td>
</tr>