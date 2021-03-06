<?php
/*   _            _          _ ____   ___  _____
 *  | |          | |        | |___ \ / _ \| ____|
 *  | |      __ _| |__   ___| | __) | | | | |__
 *  | |     / _` | '_ \ / _ \ ||__ <|  -  |___ \
 *  | |____| (_| | |_) |  __/ |___) |     |___) |
 *  |______|\__,_|_.__/ \___|_|____/ \___/|____/
 *
 *  Copyright Label305 B.V. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace spec\Label305\AujaLaravel\Database;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery\Mock;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MySQLDatabaseHelperSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('Label305\AujaLaravel\Database\MySQLDatabaseHelper');
    }

    function it_can_return_cached_has_table_info(Table $table) {
        Cache::shouldReceive('has')->withAnyArgs()->once()->andReturn(true);
        Cache::shouldReceive('get')->withAnyArgs()->once(2)->andReturn(['Table' => $table]);

        $this->hasTable('Table')->shouldBe(true);

        \Mockery::close();
    }

    function it_can_return_computed_has_table_info() {
        Cache::shouldReceive('has')->withAnyArgs()->once()->andReturn(false);
        Cache::shouldReceive('put')->withAnyArgs()->once();

        $table = \Mockery::mock('Doctrine\DBAL\Schema\Table');
        $table->shouldReceive('getName')->andReturn('Table');

        $schemaManager = \Mockery::mock('Doctrine\DBAL\Schema\AbstractSchemaManager');
        DB::shouldReceive('getDoctrineSchemaManager')->once()->andReturn($schemaManager);
        $schemaManager->shouldReceive('listTables')->once()->andReturn(['Table' => $table]);

        $this->hasTable('Table')->shouldReturn(true);

        \Mockery::close();
    }

    function it_can_return_cached_column_listing_info() {
        $table = \Mockery::mock('Doctrine\DBAL\Schema\Table');
        $table->shouldReceive('getColumns')->andReturn(['id' => '']);

        Cache::shouldReceive('has')->withAnyArgs()->once()->andReturn(true);
        Cache::shouldReceive('get')->withAnyArgs()->once(2)->andReturn(['Table' => $table]);

        $this->getColumnListing('Table')->shouldBeArray();
        $this->getColumnListing('Table')->shouldHaveCount(1);
        $this->getColumnListing('Table')->shouldContain('id');

        \Mockery::close();
    }

    function it_can_return_computed_column_listing_info() {
        Cache::shouldReceive('has')->withAnyArgs()->once()->andReturn(false);
        Cache::shouldReceive('put')->withAnyArgs()->once();

        $table = \Mockery::mock('Doctrine\DBAL\Schema\Table');
        $table->shouldReceive('getName')->andReturn('Table');
        $table->shouldReceive('getColumns')->andReturn(['id' => '']);

        $schemaManager = \Mockery::mock('Doctrine\DBAL\Schema\AbstractSchemaManager');
        DB::shouldReceive('getDoctrineSchemaManager')->once()->andReturn($schemaManager);
        $schemaManager->shouldReceive('listTables')->once()->andReturn(['Table' => $table]);

        $this->getColumnListing('Table')->shouldBeArray();
        $this->getColumnListing('Table')->shouldHaveCount(1);
        $this->getColumnListing('Table')->shouldContain('id');

        \Mockery::close();
    }

    function it_can_return_cached_column_type_info() {
        $table = \Mockery::mock('Doctrine\DBAL\Schema\Table');
        $table->shouldReceive('getColumns')->andReturn(['id' => '']);

        Cache::shouldReceive('has')->withAnyArgs()->once()->andReturn(true);
        Cache::shouldReceive('get')->withAnyArgs()->once(2)->andReturn(['Table' => $table]);

        $this->getColumnListing('Table')->shouldBeArray();
        $this->getColumnListing('Table')->shouldHaveCount(1);
        $this->getColumnListing('Table')->shouldContain('id');

        \Mockery::close();
    }

    function it_can_return_computed_column_type_info() {
        Cache::shouldReceive('has')->withAnyArgs()->once()->andReturn(false);
        Cache::shouldReceive('put')->withAnyArgs()->once();

        $type = \Mockery::mock('Doctrine\DBAL\Types\Type');
        $type->shouldReceive('getName')->andReturn('Type');

        $column = \Mockery::mock('Doctrine\DBAL\Schema\Column');
        $column->shouldReceive('getName')->andReturn('Column');
        $column->shouldReceive('getType')->andReturn($type);

        $table = \Mockery::mock('Doctrine\DBAL\Schema\Table');
        $table->shouldReceive('getName')->andReturn('Table');
        $table->shouldReceive('getColumn')->withAnyArgs()->andReturn($column);

        $schemaManager = \Mockery::mock('Doctrine\DBAL\Schema\AbstractSchemaManager');
        DB::shouldReceive('getDoctrineSchemaManager')->once()->andReturn($schemaManager);
        $schemaManager->shouldReceive('listTables')->once()->andReturn(['Table' => $table]);

        $this->getColumnType('Table', 'Column')->shouldBe('Type');

        \Mockery::close();
    }

}
