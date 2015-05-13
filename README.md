# Finger Overview

Package contain migrations, seeds and models for Finger (HR). Documentation will be written in wiki.

# Installation

composer.json:
```
	"thunderid/finger": "dev-master"
```

run
```
	composer update
```

```
	composer dump-autoload
```

# Usage

service provider
```
'ThunderID\Finger\FingerServiceProvider'
```

migration
```
  php artisan migrate --path=vendor/thunderid/finger/src/migrations
```

seed (run in mac or linux)
```
  php artisan db:seed --class=ThunderID\\Finger\\seeds\\DatabaseSeeder
```

seed (run in windows)
```
  php artisan db:seed --class='\ThunderID\Finger\seeds\DatabaseSeeder'
```

# Developer Notes for UI
## Table Finger

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign Key From Person, Integer, Required
 *	left_thumb						: 
 *	left_index_finger				: 
 *	left_middle_finger				: 
 *	left_ring_finger				: 
 *	left_little_finger				: 
 *	right_thumb						: 
 *	right_index_finger				: 
 *	right_middle_finger				: 
 *	right_ring_finger				: 
 *	right_little_finger				: 
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	1 Relationship belongsTo 
	{
		Person
	}

/* ----------------------------------------------------------------------
 * Document Fillable :
	left_thumb
	left_index_finger
	left_middle_finger
	left_ring_finger
	left_little_finger
	right_thumb
	right_index_finger
	right_middle_finger
	right_ring_finger
	right_little_finger

/* ----------------------------------------------------------------------
 * Document Observe :

/* ----------------------------------------------------------------------
 * Document Searchable :
 * 	id 								: Search by id, parameter => string, id
	personid 						: Search by person_id, parameter => string, person_id
	withattributes					: Search with relationship, parameter => array of relationship (ex : ['chart', 'person'], if relationship is belongsTo then return must be single object, if hasMany or belongsToMany then return must be plural object)

/* ----------------------------------------------------------------------