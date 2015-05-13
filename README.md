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
 * 	chart_id 						: Foreign Key From Chart, Integer, Required
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	status 		 					: Enum contract or trial or internship or permanent or previous
 * 	start 							: Date, Y-m-d, Required
 * 	end 							: Date, Y-m-d
 * 	reason_end_job 					: required if end present
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	2 Relationships belongsTo 
	{
		Chart
		Person
	}

/* ----------------------------------------------------------------------
 * Document Fillable :
 * 	chart_id
	status
	start
	end
	reason_end_job

/* ----------------------------------------------------------------------
 * Document Searchable :
 * 	id 								: Search by id, parameter => string, id
	chartid 						: Search by chart_id, parameter => string, chart_id
	personid 						: Search by person_id, parameter => string, person_id
	withattributes					: Search with relationship, parameter => array of relationship (ex : ['chart', 'person'], if relationship is belongsTo then return must be single object, if hasMany or belongsToMany then return must be plural object)

/* ----------------------------------------------------------------------