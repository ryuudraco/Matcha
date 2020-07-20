<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

use \Src\Utils\DB;

class MigrationTool {

	const COLOR_GREEN 	= '1;32';
	const COLOR_RED 	= '1;31';
	const COLOR_WHITE   = '1;37';

	private $previous_migrations = [];

	private function __construct()
	{
		# ...
	}

	private function initialise()
	{
		// Check if the migrations table exists
		$db_name = getenv('DB_NAME');
		$migration_table_exists = DB::select("
			SELECT table_name 
			FROM information_schema.tables 
			WHERE table_schema = ? 
			AND table_name = 'migrations';
		", [$db_name]);

		// If it does, output as such and continue
		if (count($migration_table_exists) == 1) {
			$this->info('Migration table already exists.');
		} else {
			// if it doesn't, create the migrations table
			DB::execute('create table `migrations` (
				`file` varchar(255) not null, 
				`migrated_at` timestamp not null
			)');
			$this->success('Migration table created successfully.');
		}

		$this->loadPreviousMigrations();
	}

	private function loadPreviousMigrations()
	{
		$migrations_already_run = DB::select('SELECT file, migrated_at FROM migrations');

		// Load all the migrations into an easy to use array that we can reference when iterating over the files
		foreach ($migrations_already_run as $migration) {
			$migration_file = $migration['file'];
			$this->previous_migrations[$migration_file] = $migration['migrated_at'];
		}
	}

	private function runMigrations()
	{
		/**
		 * by default scandir sorts them in descending order, and because our migrations are timestamps as YYYYMMDD-HHMM_name_of_migration
		 * it should all be able to run in order based off of the timestamp at the beginning.
		 */
		$migration_files = array_filter(scandir(__DIR__ . '/migrations'), function ($file) {
			/** 
			 * Check the pathinfo() of the file so that we can get the extension, and make sure it's a sql.
			 */
			return pathinfo($file)['extension'] == 'sql';
		});

		foreach ($migration_files as $migration_file) {
			if (empty($this->previous_migrations[$migration_file])) {
				$this->executeMigration($migration_file);
			}
		}
	}

	private function executeMigration($migration_file)
	{
		$migration_sql = file_get_contents(__DIR__ . '/migrations/' . $migration_file, 'r');

		$this->info('Migrating `' . $migration_file . '`...');

		// Execute the SQL from the migration file
		DB::execute($migration_sql);

		// Log it in the `migrations` table
		DB::execute('INSERT INTO migrations (file, migrated_at) VALUES (?, ?)', [
			$migration_file,
			date('Y-m-d H:i:s'),
		]);
		$this->success('Migrated `' . $migration_file . '`.');
	}

	private function info($message)
	{
		$this->output($message, self::COLOR_WHITE);
	}

	private function success($message)
	{
		$this->output($message, self::COLOR_GREEN);
	}

	private function danger($message)
	{
		$this->output($message, self::COLOR_RED);
	}

	/**
	 * This is a basic console printer with colors.
	 * It's all related to special PHP CLI color codes that start with \033[{code}m and end with \033[0m
	 * More can be read about it at https://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/
	 */
	private function output($message, $color)
	{
		echo "[" . date('Y-m-d H:i:s') . "] \033[" . $color . "m" . $message . PHP_EOL . "\033[0m";
	}

	public static function run()
	{
		$tool = new MigrationTool();
		$tool->initialise();

		$tool->runMigrations();
	}
}

MigrationTool::run();
