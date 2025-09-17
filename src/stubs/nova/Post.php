<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\File;
use Murdercode\TinymceEditor\TinymceEditor;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Post>
     */
    public static $model = \App\Models\Post::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
            ->rules('required', 'max:255')
            ->sortable(),

            Slug::make('Slug')
            ->from('Title')
            ->help('Leave empty to auto complete')
            ->onlyOnForms()
            ->rules('required')
            ->creationRules('unique:posts,slug')
            ->updateRules('unique:posts,slug,{{resourceId}}'),

            BelongsTo::make('Category')
            ->filterable()
            ->sortable(),

            TinymceEditor::make('Description', 'body')
            ->rules(['required']),
            

            Text::make('Source')
            ->rules('max:255')
            ->hideFromIndex(),

            File::make('Main Image', 'main_image_upload')
                ->store(function ($request, $model) {
                    if ($request->hasFile('main_image_upload')) {
                        $model->addMediaFromRequest('main_image_upload')
                            ->toMediaCollection('image', 'public');
                    }
                    return [];
                })
                ->onlyOnForms()
                ->rules('required')
                ->help('Main image (7:5 ratio recommended)'),

            File::make('Gallery Image', 'gallery_upload')
                ->store(function ($request, $model) {
                    if ($request->hasFile('gallery_upload')) {
                        $model->addMediaFromRequest('gallery_upload')
                            ->toMediaCollection('gallery', 'public');
                    }
                    return [];
                })
                ->onlyOnForms()
                ->help('Upload a gallery image (3:2 ratio recommended)'),

            Text::make('Main Image Preview', function () {
                $media = $this->getFirstMedia('image');
                return $media ? '<img src="' . $media->getFullUrl('thumb') . '" style="max-width:200px; border-radius:8px;" />' : 'No image';
            })->asHtml()->onlyOnDetail(),

            Text::make('Gallery Preview', function () {
                $mediaItems = $this->getMedia('gallery');
                if ($mediaItems->isEmpty()) return 'No images';
                $gallery = '';
                foreach ($mediaItems as $media) {
                    $gallery .= "<img src='{$media->getFullUrl('thumb')}' style='max-width:120px; border-radius:8px; margin:5px;' />";
                }
                return $gallery;
            })->asHtml()->onlyOnDetail(),

            Select::make('Status')
            ->options([
                'PUBLISHED' => 'Published',
                'DRAFT' => 'Draft',
            ])
            ->default('DRAFT')
            ->rules('required')
            ->filterable()
            ->onlyOnForms(),

            Badge::make('Status')->map([
                'PUBLISHED' => 'success',
                'DRAFT'     => 'warning',
            ])->withIcons(),

            Boolean::make('Featured')
            ->default(0)
            ->filterable(),

            DateTime::make('Date', 'created_at')
            ->rules('required', 'date')
            ->default(\Carbon\Carbon::now()),

            new Panel('SEO Details', [
                Text::make('Title', 'seo_title')
                ->rules('max:255')
                ->onlyOnForms(),

                Text::make('Description', 'seo_description')
                ->rules('max:255')
                ->onlyOnForms(),

                Text::make('Keywords', 'seo_keywords')
                ->rules('max:255')
                ->onlyOnForms()
            ]),


        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
