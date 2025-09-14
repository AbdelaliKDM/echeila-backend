<!-- BEGIN: My Custom JS-->

{{--datatable initialization--}}
<script>
  function initializeDataTable(route, columns, filters = {}, tableId = "#laravel_datatable") {
    return $(tableId).DataTable({
      language: {{ \Illuminate\Support\Js::from(__('datatable')) }},
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: route,
        type: "GET",
        data: function (d) {
          Object.assign(d, filters); // Merge filter values dynamically
        }
      },
      columns: [
        {
          data: null,
          name: 'number',
          orderable: false,
          searchable: false,
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        ...columns.map(column => {
          return column === 'action'
            ? { data: column, name: column, orderable: false, searchable: false }
            : { data: column, name: column };
        })
      ]
    });
  }
</script>

{{--delete confirmation--}}
<script>
  function handleActionConfirmation(attr, confirmBtnText, formId, actionClass, actionUrlPlaceholder = ':id', value = null) {
    $(document).on('click', actionClass, function () {
      var id = $(this).attr(attr);
      Swal.fire({
        title: "@lang('app.are_you_sure')",
        text: "@lang('app.you_wont_be_able_to_revert_this')",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmBtnText,
        cancelButtonText: "@lang('app.cancel')",
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          var form = $(formId);
          if (actionUrlPlaceholder) {
            form.attr('action', form.attr('action').replace(actionUrlPlaceholder, id));
            if (value) {
              form.attr('action', form.attr('action').replace(':' + value, value));
            }
          }
          form.submit();
        }
      });
    });
  }
</script>

{{--text editor--}}
<script>
  let quill; // Declare globally

  function createTextEditor(elementId) {
    const options = {
      debug: 'info', // Debug level
      modules: {
        toolbar: [
          [{ 'font': [] }],
          [{ 'size': ['small', false, 'large', 'huge'] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'color': [] }, { 'background': [] }],
          [{ 'script': 'sub' }, { 'script': 'super' }],
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
          [{ 'align': [] }],
          ['blockquote', 'code-block'],
          [{ 'list': 'ordered' }, { 'list': 'bullet' }],
          [{ 'indent': '-1' }, { 'indent': '+1' }],
          [{ 'direction': 'rtl' }],
          ['link', 'image', 'video'],
          ['clean']
        ]
      },
      placeholder: "@lang('app.write_something')",
      readOnly: false,
      theme: 'snow'
    };

    quill = new Quill(elementId, options); // Initialize Quill and assign it globally
  }
</script>
<!-- END: My Custom JS-->