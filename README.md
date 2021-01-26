# Vispobish
Add [tree structure](https://en.wikipedia.org/wiki/Tree_(data_structure)) to Eloquent models.
In this package try to use optimize queries.

## Installing
just run below command:
```sh
composer require aminsamadzadeh/simorgh
```

## Usage

Create migration.
```php
...
public function up()
{
	Schema::table('categories', function (Blueprint $table) {
		// vispobish pckage
		$table->string('path')->nullable(); // save path of tree
		$table->unsignedInteger('parent_id')->nullable();
		$table->foreign('parent_id')->references('id')->on('categories');
	});
}
...
```

Add `Treeable` trait to model.
```php
...
use Illuminate\Database\Eloquent\Model;
use AminSamadzadeh\Vispobish\Treeable;

class Category extends Model
{
    use Treeable;
}
...
```

### Named Path

if you want save path with spicial name you can add `public $namedPathWith` property to your model and add `named_path` to migration.

```php
...
public function up()
{
	Schema::table('categories', function (Blueprint $table) {
		$table->string('named_path')->unique(); // just used when set $pahtNamedWith
	});
}
...
```
```php
...
class Category extends Model
{
    use Treeable;
    public $namedPathWith = 'name';
}
...
```
