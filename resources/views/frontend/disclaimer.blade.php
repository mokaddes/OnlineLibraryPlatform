@extends('frontend.layouts.app')
@section('title')
    {{ $settings->site_name ?? 'Page header' }}
@endsection

@section('meta')
    <meta property="og:title" content="{{ $og_title }}" />
    <meta property="og:description" content="{{ $og_description }}" />
    <meta property="og:image" content="{{ asset($og_image) }}" />
@endsection

@push('style')
@endpush

@section('content')
    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb_sec">
        <div class="container">
            <div class="breadcrumb_nav text-center">
                <h5>Frachises</h5>
                <h2>Disclaimer</h2>
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= disclaimer start  ============================ -->
    <div class="disclaimer_sec mb-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="page_content">
                    <div class="content_wrap mb-5">
                        <h3>What is disclaimer</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eget nunc ut neque
                            vestibulum
                            pellentesque. Vivamus vel mauris a nibh pulvinar laoreet. Curabitur dignissim mattis mi vel
                            dictum. In at auctor urna, gravida blandit nisi. Aenean accumsan, augue id venenatis mollis,
                            eros arcu tempus dolor, eget tempus sem velit et quam. Nam consectetur consectetur quam sed
                            rhoncus. Aenean velit justo, varius ut arcu at, tincidunt lobortis lorem. Sed sollicitudin
                            aliquet turpis at vestibulum. Ut in congue sapien. Sed vitae lectus id dolor pellentesque
                            aliquet. Sed lectus massa, luctus ac ultricies id, dictum sed felis. Nam dignissim, diam a
                            pharetra dapibus, risus dolor finibus augue, ut tincidunt turpis tellus eget est.

                            <strong>email
                                info@gmail.com</strong>
                        </p>
                    </div>
                    <div class="content_wrap mb-5">
                        <h3>Where can I get some?</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                            been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                            galley of type and scrambled it to make a type specimen book. It has survived not only five
                            centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                            passages, and more recently with desktop publishing software like Aldus PageMaker including
                            versions of Lorem Ipsum</p>
                    </div>
                    <div class="content_wrap mb-5">
                        <h3>Where does it come from?</h3>
                        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of
                            classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a
                            Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure
                            Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the
                            word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from
                            sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and
                            Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very
                            popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit
                            amet..", comes from a line in section 1.10.32</p>
                    </div>
                    <div class="content_wrap">
                        <h3>Why do we use it?</h3>
                        <p>There are many variations of passages of Lorem Ipsum available, but the majority have
                            suffered alteration in some form, by injected humour, or randomised words which don't look
                            even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be
                            sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum
                            generators on the Internet tend to repeat predefined chunks as necessary, making this the
                            first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined
                            with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable.
                            The generated Lorem Ipsum is therefore always free from repetition, injected humour, or
                            non-characteristic words etc.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ======================= disclaimer end  ============================ -->
@endsection
@push('style')
@endpush
