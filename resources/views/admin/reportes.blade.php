@extends('admin.panel')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="card-body">
                @if (Session::has('admin'))
                <iframe src="https://lookerstudio.google.com/embed/reporting/97f4f19b-30b2-44e8-b82f-42dd9c5beb69/page/p_rbgksvrjmd" width="100%" height="1000" frameborder="0"></iframe>
                @else
                    {{-- NO TIENES PERMISO --}}
                    <div class="alert alert-danger" role="alert">
                        ¡No tienes permiso para ver esta página!
                    </div>
                @endif
            </div>

        </div>

    </section>


@endsection
