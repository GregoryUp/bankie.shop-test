@include('parts/header', ['title' => 'Загрузка картинки'])
<link href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/css/lightgallery.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.2/lightgallery.min.js"></script>
<div class="container">

    <nav class="navbar navbar-light">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ request()->query('sortBy') === 'name' ? 'active' : '' }}" href="/list?sortBy=name">Имя</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->query('sortBy') === 'new' ? 'active' : '' }}" href="/list?sortBy=new">Новые</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->query('sortBy') === 'old' ? 'active' : '' }}" href="/list?sortBy=old">Старые</a>
            </li>
        </ul>
    </nav>

    <div id="lightgallery">
        <div class="row">
            @foreach($files as $file)
            <div class="col-6 col-md-3">
                <div class="card mb-4 card-image-item">
                    <a class="img-item" data-src="{{ 'storage/images/' . $file['name'] }}" data-lg-size="1600-2400">
                        <img src="/preview?id={{$file['id']}}" class="card-img-top" alt="{{$file['name']}}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{$file['name']}}</h5>
                        <p class="card-text">Дата загрузки: {{ $file['created_at'] }}</p>
                        <a href="/download?id={{$file['id']}}" class="btn btn-primary">Скачать ZIP</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<style>
    .card-image-item {
        width: 300px;
        height: 500px;
    }

    @media (max-width: 1200px) {
        .card-image-item {
            width: 200px;
            height: 400px;
        }
    }

    @media (max-width: 990px) and (min-width: 780px) {
        .card-image-item {
            width: 150px;
            height: 300px;
        }

        .card-image-item .card-title {
            font-size: .875em;
        }

        .card-image-item .card-text {
            font-size: .875em
        }
    }
</style>

<script type="text/javascript">
    lightGallery(document.getElementById('lightgallery'), {
        selector: '.img-item',
        animateThumb: false,
        zoomFromOrigin: false,
        download: false
    });
</script>
@include('parts/footer')