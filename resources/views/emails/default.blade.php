<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $data['subject'] ?? 'E-mail bericht' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        h1 {
            color: #222;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 16px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(isset($data['logo']))
            <img src="{{ $data['logo'] }}" alt="Logo" class="logo">
        @endif
    </div>
    
    <div class="content">
        @if(isset($data['title']))
            <h1>{{ $data['title'] }}</h1>
        @endif
        
        @if(isset($data['html']))
            {!! $data['html'] !!}
        @else
            @if(isset($data['greeting']))
                <p>{{ $data['greeting'] }}</p>
            @else
                <p>Beste,</p>
            @endif
            
            @if(isset($data['body']))
                <p>{!! $data['body'] !!}</p>
            @endif
            
            @if(isset($data['buttonText']) && isset($data['buttonUrl']))
                <a href="{{ $data['buttonUrl'] }}" class="button">{{ $data['buttonText'] }}</a>
            @endif
            
            @if(isset($data['closing']))
                <p>{{ $data['closing'] }}</p>
            @else
                <p>Met vriendelijke groet,<br>{{ $data['sender'] ?? config('app.name') }}</p>
            @endif
        @endif
    </div>
    
    <div class="footer">
        @if(isset($data['footer']))
            {!! $data['footer'] !!}
        @else
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Alle rechten voorbehouden.</p>
        @endif
    </div>
</body>
</html>
