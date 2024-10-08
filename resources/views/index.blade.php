<?php

//echo "Space challenge";
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>
        {{ $translationHelper->translate('welcome.site_title',"Embers for change") }}
    </title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://public.tableau.com/javascripts/api/tableau-2.8.0.min.js"></script>

</head>

<body>
    <header class="text-gray-600 body-font">
        <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
            <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                <img src="{{ asset('/public/logo.png') }}" style="height:75px;width:75px"></img>
                <span
                    class="ml-3 text-xl">{{ $translationHelper->translate('welcome.site_header',"Embers for change") }}</span>
            </a>
            <nav
                class="md:mr-auto md:ml-4 md:py-1 md:pl-4 md:border-l md:border-gray-400	flex flex-wrap items-center text-base justify-center">
                <a href="/"
                    class="mr-5 hover:text-gray-900">{{ $translationHelper->translate('welcome.link_home',"Home") }}</a>
                <a href="https://youtube.com/@embersofchange?si=s4bPoScQhxG7mprA" target="_blank"
                    class="mr-5 hover:text-gray-900">{{ $translationHelper->translate('welcome.link_traing',"E-Learning") }}</a>
                <a href="https://www.youtube.com/@UNWomenTrainingCentre" target="_blank"
                    class="mr-5 hover:text-gray-900">{{ $translationHelper->translate('welcome.link_contact',"External Sources") }}</a>
                <a href="#" class="mr-5 hover:text-gray-900">{{ app()->getLocale() }}</a>
                <select id="language-selector" class="mr-5 hover:text-gray-900" style="max-width: 150px;">

                    @foreach($languages as $language)
                        @if(app()->getLocale()== $language['code'])
                            <option selected value="{{ $language['code'] }}">
                                {{ $language['name'] }}
                                ({{ $language['nativeName'] }})</option>
                        @else
                            <option value="{{ $language['code'] }}">
                                {{ $language['name'] }}
                                ({{ $language['nativeName'] }})</option>
                        @endif
                    @endforeach
                </select>
                <script>
                    // Handle language change
                    $('#language-selector').on('change', function () {
                        var selectedLanguage = $(this).val();

                        // Make an AJAX request to update the locale
                        $.ajax({
                            url: "{{ route('update-locale') }}",
                            type: "POST",
                            data: {
                                locale: selectedLanguage,
                                _token: '{{ csrf_token() }}' // Laravel CSRF protection
                            },
                            success: function (response) {
                                // Optionally reload the page to apply the new locale
                                location.reload();
                            },
                            error: function () {
                                alert('Failed to update language.');
                            }
                        });
                    });

                </script>
            </nav>
            
        </div>
    </header>
    <section class="text-gray-600 body-font">
        <div class="container mx-auto flex px-5 py-24 md:flex-row flex-col items-center">
            <div
                class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left mb-16 md:mb-0 items-center text-center">
                <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                    {{ $translationHelper->translate('welcome.title',"Welcome to the Global Women") }}
                    <br
                        class="hidden lg:inline-block">{{ $translationHelper->translate('welcome.head',"Training Centre eLearning Campus!") }}
                </h1>
                <p class="mb-8 leading-relaxed">
                    {{ $translationHelper->translate('welcome.doc_desc',"The Global Women Training Centre eLearning Campus is a global and innovative online platform for training for gender equality. It is open to everybody interested in using training or learning as a means to advance gender equality, women’s empowerment and women’s rights.") }}
                </p>
                <div class="flex justify-center">
                    <button
                        class="inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                        onclick="window.open('https://en.wikipedia.org/wiki/List_of_emergency_telephone_numbers', '_blank')">{{ $translationHelper->translate('welcome.link_help',"Help line") }}</button>
                    <button
                        class="ml-4 inline-flex text-gray-700 bg-gray-100 border-0 py-2 px-6 focus:outline-none hover:bg-gray-200 rounded text-lg"
                        onclick="window.open('https://gdacs.org/Alerts/default.aspx', '_blank')">{{ $translationHelper->translate('welcome.link_update',"Climate updates") }}</button>
                </div>
            </div>
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                <img class="object-cover object-center rounded" alt="hero"
                    src="{{ asset('/public/home.jpeg') }}">
            </div>

        </div>
        
    </section>
</body>

</html>
