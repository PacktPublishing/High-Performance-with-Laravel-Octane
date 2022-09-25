<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel</title>
</head>

<body class="antialiased">
  <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

      <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1">

          <div class="p-6">
            <div class="flex items-center">

              <div class="ml-4 text-lg leading-7 font-semibold">{{ $title}}</div>
            </div>

            <div class="ml-12">
              <div class="mt-2 text-gray-900 dark:text-gray-900 text-2xl">
                {{ $description }}
              </div>
              <div class="mt-2 text-gray-900 dark:text-gray-900 text-2xl">
                {{ $time / 1_000_000 }} milliseconds
              </div>
            </div>
          </div>


        </div>
      </div>

      <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
        <div class="text-center text-sm text-gray-500 sm:text-left">
        </div>

        <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
          Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </div>
      </div>
    </div>
  </div>
</body>

</html>
