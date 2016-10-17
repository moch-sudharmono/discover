<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('categories')->delete();

		\DB::table('categories')->insert(array (
			0 =>
			array (
				'id' => '2',
				'name' => 'First Category',
				'alias' => 'first-category',
				'type' => 'post',
				'description' => 'Lorem Ipsum',
				'status' => 'published',
				'created_by' => '1',
				'updated_by' => NULL,
				'created_at' => '2014-03-29 21:45:00',
				'updated_at' => '2014-03-29 21:45:00',
			),
			1 =>
			array (
				'id' => '3',
				'name' => 'Another Category',
				'alias' => 'another-category',
				'type' => 'page',
				'description' => 'Lorem Ipsum',
				'status' => 'published',
				'created_by' => '1',
				'updated_by' => NULL,
				'created_at' => '2014-03-29 21:45:00',
				'updated_at' => '2014-03-29 21:45:00',
			),
		));
	}

}
