<?php

declare(strict_types=1);

namespace NovaListCard;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Card;

class ListCard extends Card
{
    public $width = '1/3';

    public $resource;

    public $relationship;

    public $aggregate;

    public $aggregateColumn;

    public $limit = 5;

    public $orderColumn = 'created_at';

    public $orderDirection = 'desc';

    public $heading = [];

    public $subtitleEnabled = false;

    public $subtitleColumn;

    public $valueColumn;

    public $valueFormat;

    public $valueFormatter;

    public $timestampEnabled = false;

    public $timestampColumn;

    public $timestampFormat;

    public $footerLinkText;

    public $footerLinkType;

    public $footerLinkParams = [];

    public $classes;

    public function component()
    {
        return 'nova-list-card';
    }

    public function resource($resource)
    {
        $this->resource = $resource;
        $this->classes($resource::uriKey());

        return $this;
    }

    public function withCount($relationship)
    {
        $this->aggregate = 'count';
        $this->relationship = $relationship;

        return $this;
    }

    public function withSum($relationship, $column)
    {
        $this->aggregate = 'sum';
        $this->relationship = $relationship;
        $this->aggregateColumn = $column;

        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orderColumn = $column;
        $this->orderDirection = $direction;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function heading($left, $right = null)
    {
        $this->heading = ['left' => $left, 'right' => $right];
        $this->classes(Str::slug($left));

        return $this;
    }

    public function subtitle($column = null)
    {
        $this->subtitleEnabled = true;
        $this->subtitleColumn = $column;

        return $this;
    }

    public function value($column, $format = null, $formatter = 'numerial')
    {
        $this->valueColumn = $column;
        $this->valueFormat = $format;
        $this->valueFormatter = $formatter;

        return $this;
    }

    public function timestamp($column = 'created_at', $format = 'MM/DD/YYYY')
    {
        $this->timestampEnabled = true;
        $this->timestampColumn = $column;
        $this->timestampFormat = $format;

        return $this;
    }

    public function viewAll()
    {
        return $this->footerRoute(__('View All'), 'index');
    }

    public function viewAllLens($uriKey)
    {
        return $this->footerRoute(__('View All'), 'lens', ['lens' => $uriKey]);
    }

    public function footerLink($text, $href, $target = '_blank')
    {
        return $this->footerRoute($text, 'href', [
            'text' => $text,
            'href' => $href,
            'target' => $target,
        ]);
    }

    public function footerRoute($text, $type, $params = [])
    {
        $this->footerLinkText = $text;
        $this->footerLinkType = $type;
        $this->footerLinkParams = $params;

        return $this;
    }

    public function zebra()
    {
        return $this->classes('zebra');
    }

    public function classes($classes)
    {
        $this->classes = $this->classes .= ' '.$classes;

        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge([
            'limit' => $this->limit,
            'uri_key' => $this->uriKey(),
            // 'uri_key' => $this->resource::uriKey(),
            'relationship' => $this->relationship,
            'aggregate' => $this->aggregate,
            'aggregate_column' => $this->aggregateColumn,
            'order_column' => $this->orderColumn,
            'order_direction' => $this->orderDirection,
            'classes' => $this->classes,
            'heading' => $this->heading,
            'subtitle_enabled' => $this->subtitleEnabled,
            'subtitle_column' => $this->subtitleColumn,
            'value_column' => $this->valueColumn,
            'value_format' => $this->valueFormat,
            'value_formatter' => $this->valueFormatter,
            'timestamp_column' => $this->timestampColumn,
            'timestamp_enabled' => $this->timestampEnabled,
            'timestamp_format' => $this->timestampFormat,
        ], $this->footerLinkSettings(), parent::jsonSerialize());
    }

    protected function footerLinkSettings()
    {
        $settings = [
            'footer_link_text' => $this->footerLinkText,
            'footer_link_type' => $this->footerLinkType,
        ];

        if (! is_null($this->footerLinkType) && 'href' != $this->footerLinkType && ! isset($this->footerLinkParams['resourceName'])) {
            $this->footerLinkParams['resourceName'] = $this->resource::uriKey();
        }

        $settings['footer_link_params'] = $this->footerLinkParams;

        return $settings;
    }

    public function authorize(Request $request)
    {
        return true; // authorizeToViewAny
    }
}
