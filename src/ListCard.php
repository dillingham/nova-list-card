<?php

namespace NovaListCard;

use Laravel\Nova\Card;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ListCard extends Card
{
    public $id;

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

    public $viewAllEnabled = false;

    public $viewAllRoute = 'index';

    public $viewAllKey;

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
        $this->viewAllEnabled = true;

        return $this;
    }

    public function viewAllLens($uriKey)
    {
        $this->viewAllEnabled = true;
        $this->viewAllRoute = 'lens';
        $this->viewAllKey = $uriKey;

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

    public function id($id)
    {
        $this->id = $id;

        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge([
            'id' => $this->id,
            'limit' => $this->limit,
            'uri_key' => $this->resource::uriKey(),
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
            'view_all_enabled' => $this->viewAllEnabled,
            'view_all_route' => $this->viewAllRoute,
            'view_all_key' => $this->viewAllKey,
            'timestamp_column' => $this->timestampColumn,
            'timestamp_enabled' => $this->timestampEnabled,
            'timestamp_format' => $this->timestampFormat,
        ], parent::jsonSerialize());
    }

    public function authorize(Request $request)
    {
        return true; // authorizeToViewAny
    }
}
