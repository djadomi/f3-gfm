<?php

namespace Djadomi;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\StringContainerInterface;

class AlertExtension implements ExtensionInterface
{
    private const TYPES = ['note', 'tip', 'important', 'warning', 'caution'];

    public function register(\League\CommonMark\Environment\EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, [$this, 'processDocument']);
    }

    public function processDocument(DocumentParsedEvent $event): void
    {
        $document = $event->getDocument();
        $walker = $document->walker();

        while ($node = $walker->current()) {
            if ($node instanceof BlockQuote) {
                $this->checkForAlert($node);
            }
            $walker->next();
        }
    }

    private function checkForAlert(BlockQuote $blockQuote): void
    {
        $firstParagraph = null;
        foreach ($blockQuote->children() as $child) {
            if ($child instanceof Paragraph) {
                $firstParagraph = $child;
                break;
            }
        }

        if ($firstParagraph === null) {
            return;
        }

        $text = $this->getTextContent($firstParagraph);
        
        foreach (self::TYPES as $type) {
            $pattern = '/^\[!' . strtoupper($type) . '\]/i';
            if (preg_match($pattern, $text)) {
                $this->convertToAlert($blockQuote, $firstParagraph, $type, $pattern);
                break;
            }
        }
    }

    private function getTextContent(Paragraph $paragraph): string
    {
        $text = '';
        foreach ($paragraph->children() as $child) {
            if ($child instanceof StringContainerInterface) {
                $text .= $child->getLiteral();
            } elseif ($child instanceof Text) {
                $text .= $child->getLiteral();
            }
        }
        return $text;
    }

    private function convertToAlert(BlockQuote $blockQuote, Paragraph $paragraph, string $type, string $pattern): void
    {
        $blockQuote->data->set('attributes', [
            'class' => 'gfm-alert gfm-alert-' . $type,
            'data-alert-type' => $type,
        ]);

        $text = $this->getTextContent($paragraph);
        $newText = preg_replace($pattern, '', $text, 1);
        
        $this->updateParagraphText($paragraph, trim($newText));
    }

    private function updateParagraphText(Paragraph $paragraph, string $newText): void
    {
        foreach ($paragraph->children() as $child) {
            if ($child instanceof StringContainerInterface || $child instanceof Text) {
                $child->setLiteral($newText);
                return;
            }
        }
        
        if ($paragraph->firstChild() === null) {
            $paragraph->appendChild(new Text($newText));
        }
    }
}
