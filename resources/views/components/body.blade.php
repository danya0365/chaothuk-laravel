<body x-data="{
    darkMode: {{ Auth::user() && Auth::user()->theme == 'dark' ? 'true' : 'false' }},
    async changeUserTheme() {
        this.darkMode = !this.darkMode
        const theme = this.darkMode ? 'dark' : 'light'
        const url = '{{ route('ajax.me.update-theme') }}';

        $.post({
            url: url,
            data: {
                theme: theme
            },
            success: function(response) {
                console.log('response',
                    response)
            },
            dataType: 'json'
        });
    }
}" :class="{ 'dark': darkMode === true }"
    class="font-kanit antialiased {{ Auth::user() && Auth::user()->theme == 'dark' ? 'dark' : '' }}">
    {{ $slot }}</body>
