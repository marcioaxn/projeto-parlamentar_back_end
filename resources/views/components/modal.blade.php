@if (Session::has('flash_message'))
        <div class="modal fade" id="minhaModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"
            style="padding-top: 150px!Important;">
            <div class="modal-dialog  modal-xl">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background: linear-gradient(135deg,#013d1a 0%,#3bad6b 100%);color: white;">
                        <p class="modal-title text-white"
                            style="margin-top: 1px!Important; margin-bottom: 1px!Important;">
                            Sucesso!</p>
                    </div>
                    <div class="modal-body">

                        @if (is_array(Session::get('flash_message')))
                            @php
                                $erros = Session::get('flash_message');
                                $contErro = 1;
                            @endphp
                            @foreach ($erros as $erro)
                                <p>{!! $contErro . '. ' . $erro !!}</p>
                                @php
                                    $contErro++;
                                @endphp
                            @endforeach
                        @else
                            {!! Session::get('flash_message') !!}
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var minhaModal = new bootstrap.Modal(document.getElementById('minhaModal'));
            minhaModal.show();
        </script>

        <?php Session::forget('flash_message'); ?>
    @endif
