# Email System

This document provides detailed information about the email system within the Manta Laravel Flux CMS package.

## MailDefault Class

The `MailDefault` class is a highly customizable and reusable mail template that serves as a foundation for sending various types of emails. It supports a wide range of configuration options through a simple array-based interface.

### Basic Usage

```php
use Manta\FluxCMS\Mail\MailDefault;
use Illuminate\Support\Facades\Mail;

// Send a simple email
Mail::to('recipient@example.com')->send(new MailDefault([
    'subject' => 'Welcome to our service',
    'title' => 'Thank you for registering',
    'body' => 'We are glad you have registered. <br>Your account is now active.',
    'buttonText' => 'Visit your account',
    'buttonUrl' => 'https://example.com/account'
]));
```

### Configuration Options

The `MailDefault` class accepts an array of configuration options in its constructor. The following options are supported:

#### Envelope Options

| Option | Type | Description |
|--------|------|-------------|
| `subject` | string | The subject of the email |
| `from` | string/array | The sender's email address (string) or array with 'address' and 'name' keys |
| `replyTo` | string/array | Reply-to address (string) or array with 'address' and 'name' keys |
| `cc` | string/array | Carbon copy recipients as a string, array of strings, or array of address/name pairs |
| `bcc` | string/array | Blind carbon copy recipients as a string, array of strings, or array of address/name pairs |
| `tags` | array | Tags for email categorization (supported by some email providers) |
| `metadata` | array | Metadata for tracking or analytics (supported by some email providers) |

#### Content Options

| Option | Type | Description |
|--------|------|-------------|
| `view` | string | Custom blade view to use (defaults to 'manta-cms::emails.default') |
| `html` | string | Raw HTML content to use instead of the template |
| `text` | string | Plain text version of the email |
| `markdown` | string | Markdown content for the email |

#### Template-Specific Options

| Option | Type | Description |
|--------|------|-------------|
| `logo` | string | URL to a logo image to display in the header |
| `title` | string | Main heading for the email |
| `greeting` | string | Greeting line (defaults to "Beste,") |
| `body` | string | Main content of the email (can include HTML) |
| `buttonText` | string | Text for the call-to-action button |
| `buttonUrl` | string | URL for the call-to-action button |
| `closing` | string | Closing line (defaults to "Met vriendelijke groet, [sender]") |
| `sender` | string | Name to use in the closing (defaults to app.name config) |
| `footer` | string | Custom footer HTML (defaults to copyright notice) |

#### Attachment Options

| Option | Type | Description |
|--------|------|-------------|
| `attachments` | array | Array of attachments to include with the email |

Attachments can be specified in several ways:

```php
'attachments' => [
    // Simple path to file
    '/path/to/file.pdf',
    
    // With additional options
    [
        'path' => '/path/to/file.pdf',
        'as' => 'document.pdf', // Custom filename
        'mime' => 'application/pdf' // Custom MIME type
    ],
    
    // From raw data
    [
        'data' => $pdfContent,
        'name' => 'report.pdf',
        'mime' => 'application/pdf'
    ],
]
```

### Advanced Examples

#### HTML Email with Custom Template

```php
Mail::to($user)->send(new MailDefault([
    'subject' => 'Monthly Newsletter',
    'view' => 'emails.newsletter', // Custom template
    'from' => ['address' => 'newsletter@example.com', 'name' => 'Our Newsletter'],
    'data' => [
        'username' => $user->name,
        'articles' => $articles
    ]
]));
```

#### Fully Customized Marketing Email

```php
Mail::to($customer->email)->send(new MailDefault([
    'subject' => 'Special Offer Just For You',
    'from' => ['address' => 'offers@example.com', 'name' => 'Special Offers'],
    'replyTo' => 'customer-service@example.com',
    'logo' => 'https://example.com/logo.png',
    'title' => 'Limited Time Offer',
    'greeting' => 'Hello ' . $customer->first_name . ',',
    'body' => 'We have a special offer just for you! <br><br>Get <strong>20% off</strong> your next purchase.',
    'buttonText' => 'Claim Your Discount',
    'buttonUrl' => 'https://example.com/offers/' . $customer->discount_code,
    'closing' => 'Happy shopping!<br>The Example Team',
    'footer' => 'This offer is valid until ' . $expiryDate . '. Terms and conditions apply.',
    'tags' => ['marketing', 'discount', 'special-offer'],
    'metadata' => [
        'campaign_id' => 'summer2025',
        'user_type' => $customer->type
    ]
]));
```

#### Sending an Order Confirmation with Attachments

```php
Mail::to($order->customer_email)->send(new MailDefault([
    'subject' => 'Order Confirmation #' . $order->number,
    'title' => 'Thank you for your order!',
    'body' => 'We have received your order and it is being processed.<br><br>' .
              '<strong>Order Number:</strong> ' . $order->number . '<br>' .
              '<strong>Total Amount:</strong> €' . number_format($order->total, 2),
    'buttonText' => 'Track Your Order',
    'buttonUrl' => route('orders.track', $order->tracking_number),
    'attachments' => [
        [
            'path' => $invoice->getPath(),
            'as' => 'Invoice-' . $order->number . '.pdf',
            'mime' => 'application/pdf'
        ]
    ]
]));
```

## Default Email Template

The package includes a default email template located at `resources/views/emails/default.blade.php` that provides:

- Responsive design for mobile and desktop clients
- Logo placement in the header
- Title and body content formatting
- Call-to-action button styling
- Professional footer

You can override this template by:

1. Creating your own template in your application
2. Specifying the custom view path in the `view` parameter
3. Or providing raw HTML in the `html` parameter

## Customizing the Default Template

If you want to customize the default template, you can publish it to your application:

```bash
php artisan vendor:publish --provider="Manta\FluxCMS\FluxCMSServiceProvider" --tag="views"
```

This will copy the email template to `resources/views/vendor/manta-cms/emails/default.blade.php` where you can modify it to match your branding and requirements.

## Best Practices

1. **Use the array configuration**: Take advantage of the flexible array configuration to create reusable email templates
2. **Provide both HTML and text versions**: For better email client compatibility and accessibility
3. **Keep emails concise**: Focus on a single call-to-action per email
4. **Use proper error handling**: Wrap mail sending in try-catch blocks when sending in production
5. **Consider queuing**: For bulk emails, use Laravel's queue system by implementing `ShouldQueue` on your custom mail classes

## Extending MailDefault

If you need additional functionality, you can extend the `MailDefault` class:

```php
use Manta\FluxCMS\Mail\MailDefault;

class OrderConfirmationEmail extends MailDefault
{
    public function __construct($order)
    {
        parent::__construct([
            'subject' => 'Your order #' . $order->number,
            'title' => 'Order Confirmation',
            'body' => $this->generateOrderSummaryHtml($order),
            'buttonText' => 'View Order Details',
            'buttonUrl' => route('orders.show', $order)
        ]);
    }
    
    protected function generateOrderSummaryHtml($order)
    {
        // Generate HTML for order summary
        return view('emails.partials.order-summary', compact('order'))->render();
    }
}
