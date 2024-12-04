@if (Session::get('permissao') === '0000100')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-1 pb-3 text-left d-print-none"
        id="divBtnIncluirNovaObservacao">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse"
            data-bs-target="#collapseFormNovaObservacao" aria-expanded="false"
            aria-controls="collapseFormNovaObservacao"><i class="fas fa-plus-circle"></i>
            Incluir nova observação</button>
    </div>
@else
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pt-1 pb-1 text-left d-print-none">&nbsp;</div>
@endif
