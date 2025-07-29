<?php

namespace Manta\FluxCMS\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;

class MailDefault extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Mail data voor template
     *
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     * 
     * @param array $data 
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     * 
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        $envelope = new Envelope();
        
        // Onderwerp instellen indien aanwezig
        if (isset($this->data['subject'])) {
            $envelope = $envelope->subject($this->data['subject']);
        }
        
        // Afzender instellen indien aanwezig
        if (isset($this->data['from'])) {
            if (is_array($this->data['from'])) {
                $envelope = $envelope->from(new Address($this->data['from']['address'], $this->data['from']['name'] ?? ''));
            } else {
                $envelope = $envelope->from(new Address($this->data['from']));
            }
        }
        
        // Antwoord-aan instellen indien aanwezig
        if (isset($this->data['replyTo'])) {
            if (is_array($this->data['replyTo'])) {
                $envelope = $envelope->replyTo(new Address($this->data['replyTo']['address'], $this->data['replyTo']['name'] ?? ''));
            } else {
                $envelope = $envelope->replyTo(new Address($this->data['replyTo']));
            }
        }
        
        // CC instellen indien aanwezig
        if (isset($this->data['cc'])) {
            $cc = [];
            if (is_array($this->data['cc'])) {
                foreach ($this->data['cc'] as $ccRecipient) {
                    if (is_array($ccRecipient)) {
                        $cc[] = new Address($ccRecipient['address'], $ccRecipient['name'] ?? '');
                    } else {
                        $cc[] = new Address($ccRecipient);
                    }
                }
            } else {
                $cc[] = new Address($this->data['cc']);
            }
            $envelope = $envelope->cc($cc);
        }
        
        // BCC instellen indien aanwezig
        if (isset($this->data['bcc'])) {
            $bcc = [];
            if (is_array($this->data['bcc'])) {
                foreach ($this->data['bcc'] as $bccRecipient) {
                    if (is_array($bccRecipient)) {
                        $bcc[] = new Address($bccRecipient['address'], $bccRecipient['name'] ?? '');
                    } else {
                        $bcc[] = new Address($bccRecipient);
                    }
                }
            } else {
                $bcc[] = new Address($this->data['bcc']);
            }
            $envelope = $envelope->bcc($bcc);
        }
        
        // Tags instellen indien aanwezig
        if (isset($this->data['tags']) && is_array($this->data['tags'])) {
            $envelope = $envelope->tags($this->data['tags']);
        }
        
        // Metadata instellen indien aanwezig
        if (isset($this->data['metadata']) && is_array($this->data['metadata'])) {
            $envelope = $envelope->metadata($this->data['metadata']);
        }
        
        return $envelope;
    }

    /**
     * Get the message content definition.
     * 
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        $content = new Content();
        
        // Template instellen
        if (isset($this->data['view'])) {
            $content = $content->view($this->data['view']);
        } else {
            // Standaard template
            $content = $content->view('manta-cms::emails.default');
        }
        
        // HTML content instellen indien aanwezig
        if (isset($this->data['html'])) {
            $content = $content->with('html', $this->data['html']);
        }
        
        // Tekst versie instellen indien aanwezig
        if (isset($this->data['text'])) {
            $content = $content->text($this->data['text']);
        }
        
        // Markdown instellen indien aanwezig
        if (isset($this->data['markdown'])) {
            $content = $content->markdown($this->data['markdown']);
        }
        
        return $content;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        if (isset($this->data['attachments']) && is_array($this->data['attachments'])) {
            foreach ($this->data['attachments'] as $attachment) {
                if (is_string($attachment)) {
                    // Eenvoudig pad naar bestand
                    $attachments[] = Attachment::fromPath($attachment);
                } elseif (is_array($attachment)) {
                    // Configuratie opties
                    $attach = null;
                    
                    if (isset($attachment['path'])) {
                        $attach = Attachment::fromPath($attachment['path']);
                    } elseif (isset($attachment['data']) && isset($attachment['name'])) {
                        $attach = Attachment::fromData(fn () => $attachment['data'], $attachment['name']);
                    }
                    
                    if ($attach) {
                        // Optionele eigenschappen toevoegen
                        if (isset($attachment['mime'])) {
                            $attach = $attach->withMime($attachment['mime']);
                        }
                        
                        if (isset($attachment['as'])) {
                            $attach = $attach->as($attachment['as']);
                        }
                        
                        if (isset($attachment['withDisposition'])) {
                            $attach = $attach->withDisposition($attachment['withDisposition']);
                        }
                        
                        $attachments[] = $attach;
                    }
                }
            }
        }
        
        return $attachments;
    }
}
