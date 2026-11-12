<!-- Modal que atende adicionar e editar disciplinas -->
<div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Adicionar/Editar Disciplinas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list_table_div_form">
          {{ html()->form('post', '')->open() }}
            @csrf
            @method('post')
            {{ html()->hidden('id') }}
            @php
              $modo = 'create';
            @endphp
            @foreach ($fields as $col)
              @if (empty($col['type']) || $col['type'] == 'text')
                @include('common.list-table-form-text')
              @elseif ($col['type'] == 'select')
                @include('common.list-table-form-select')
              @endif
            @endforeach
            <div class="text-right">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          {{ html()->form()->close() }}
        </div>
      </div>
    </div>
  </div>
</div>

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(document).ready(function() {

      $('#sigla').attr({
        'maxlength': 7,
        'pattern': '[A-Z]{3}[0-9]{4}',
        'title': 'Digite três letras maiúsculas seguidas de quatro números'
      }).on('input', function() {
        valor = $(this).val().toUpperCase();
        novo_valor = '';
        for (i = 0; i < valor.length && novo_valor.length < 7; i++) {
          c = valor.charAt(i);
          if (novo_valor.length < 3) {
            if (/[A-Z]/.test(c))
              novo_valor += c;
          } else {
            if (/[0-9]/.test(c))
              novo_valor += c;
          }
        }
        $(this).val(novo_valor);
      });

      $('#modalForm').on('shown.bs.modal', function() {
        $(this).find(':input[type=text]').filter(':visible:first').focus();
      });

      edit_form = function(id) {
        $.get('disciplinas/' + id
          , function(row) {
            console.log(row);
            // mudando para PUT
            $('#modalForm :input').filter("input[name='_method']").val('PUT');

            // preenchendo o form com os valores a serem editados
            var inputs = $("#modalForm :input").not(":input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
            inputs.each(function() {
              $(this).val(row[this.name]);
              console.log(this.name);
            });

            // Ajustando action
            $('#modalForm').find('form').attr('action', 'disciplinas/' + id);

            // Ajustando o title
            $('#modalLabel').html('Editar Disciplina');

            $("#modalForm").modal();
            console.log('inputs', inputs);
          });
      };

      add_form = function(id) {
          $("#modalForm :input").filter("input[type='text']").val('');

          // preenchendo o form com os valores a serem editados
          $("#modalForm select").val(id);

          // Ajustando action
          $('#modalForm').find('form').attr('action', 'disciplinas');

          $('#modalLabel').html('Adicionar Disciplina');
          $('#modalForm :input').filter("input[name='_method']").val('POST');

          $("#modalForm").modal();
      };
    });
  </script>
@endsection
