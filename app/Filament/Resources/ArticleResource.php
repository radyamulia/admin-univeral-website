<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Api\Transformers\ArticleTransformer;
use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Website';

    public static function getApiTransformer()
    {
        return ArticleTransformer::class;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state) .  "-" . Str::uuid()->toString()) : null)
                            ->live(onBlur: true)
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('lang')
                            ->required()
                            ->label("Language")
                            ->options([
                                'en' => 'English',
                                'id' => 'Bahasa Indonesia',
                            ])
                            ->columnSpan(2),
                        Forms\Components\MarkdownEditor::make('description')
                            ->required()
                            ->columnSpan(2),
                        SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio('16:9')
                            ->collection('articles/images')
                            ->columnSpan(2),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('lang')
                    ->label("Language")
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->timezone('Asia/Makassar')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
