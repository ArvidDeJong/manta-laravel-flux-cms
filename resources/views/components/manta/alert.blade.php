@props(['title', 'subject' => null])
<div class="relative mb-8 flex items-center rounded border border-blue-500 bg-blue-100 px-4 py-3 text-blue-700"
    role="alert">
    <strong class="font-bold"><i class="fa-regular fa-triangle-exclamation"></i>{{ $subject }}</strong>
    <span class="ml-2 block sm:inline">{{ $title }}</span>
    <span class="absolute bottom-0 right-0 top-0 px-4 py-3">
        <svg class="h-6 w-6 fill-current text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20">
            <title>Sluiten</title>
            <path
                d="M14.348 14.849a1 1 0 01-1.414 0L10 11.414 7.066 14.35a1 1 0 11-1.414-1.415L8.586 10 5.652 7.066a1 1 0 111.414-1.414L10 8.586l2.934-2.934a1 1 0 111.414 1.414L11.414 10l2.934 2.934a1 1 0 010 1.415z" />
        </svg>
    </span>
</div>
