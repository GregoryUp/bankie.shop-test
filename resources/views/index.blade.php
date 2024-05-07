@include('parts/header', ['title' => 'Загрузка картинки'])
<div class="container">
   <form action="/upload" method="post" enctype="multipart/form-data">
        @csrf
        <input id="main_slider" required name="main_slider[]" type="file" accept="image/*" multiple data-show-upload="false" data-show-caption="true" data-max-file-count="5" data-msg-placeholder="Select {files} for upload...">
        <button type="submit" class="btn btn-primary">Загрузить</button>
   </form>
</div>

<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/locales/LANG.js"></script>
<script>
    $("#main_slider").fileinput();
</script>
@include('parts/footer')