<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (isset($title))
        <title>{{ $title }}</title>
    @else
        <title>{{ config('app.name', 'Backend') }}</title>
    @endif

    <!-- Fonts -->

    <!-- Scripts -->
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<x-body>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white">
        @include('layouts.backend-navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
            @auth
                <div class="p-4">
                    <x-theme-toggle />
                </div>
            @endauth
        </main>
    </div>
    <script type="text/javascript">
        function alpineUploadImage(initUploadFile) {
            return {
                uploadFile: initUploadFile,
                async submitUpload() {
                    if (this.files === null) return;

                    let formData = new FormData();
                    formData.append('image', this.files[0]);
                    const uploadResponse = await $.ajax({
                        url: '{{ route('ajax.upload.image') }}',
                        type: 'POST',
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                    });

                    if (!uploadResponse.data) {
                        return;
                    }

                    const documentUploadUrl = uploadResponse.data.original

                    this.uploadFile = documentUploadUrl
                    this.files = null;
                },
                resetUpload() {
                    this.files = null;
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                },
                files: null
            }
        }

        function alpineUploadAvatar(initUploadFile) {
            return {
                uploadFile: initUploadFile,
                async submitUpload() {
                    if (this.files === null) return;

                    let formData = new FormData();
                    formData.append('avatar', this.files[0]);
                    const uploadResponse = await $.ajax({
                        url: '{{ route('ajax.upload.avatar') }}',
                        type: 'POST',
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                    });

                    if (!uploadResponse.data) {
                        return;
                    }

                    const uploadUrl = uploadResponse.data.avatar

                    this.uploadFile = uploadUrl
                    this.files = null;
                },
                resetUpload() {
                    this.files = null;
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                },
                files: null
            }
        }
    </script>
    @if (isset($javascript))
        {{ $javascript }}
    @endif
</x-body>

</html>
