<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Api\Command;


use App\Core\Command;
use App\Core\Configurator\PhpConfigurator;
use App\Core\Exception\Configurator\ConfigurationFolderMissingException;

class InstallCommand extends Command
{
    public function runCommand(): void
    {
        try {
            $config = new PhpConfigurator();
            if ($config->has('config', 'sqlite_dsn')) {
                $dbpath = \substr($config->get('config', 'sqlite_dsn'), 7);
                if (!file_exists($dbpath)) {
                    \file_put_contents($dbpath, '');
                }

                $dp = new \PDO($config->get('config', 'sqlite_dsn'), null, null, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]);
                $dp->query('CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL
);');
                if ($this->has('with-fixtures')) {
                    $sql = 'INSERT INTO users (name, email, password) VALUES ()';
                    $insert = [];
                    foreach ($this->generateData() as $d) {
                        $insert[] = "('{$d['name']}', '{$d['email']}', '{$d['password']}')";
                    }
                    $sql = str_replace("()", implode(',', $insert), $sql);
                    if ($dp->query($sql)) {
                        echo "Fixtures created." . PHP_EOL;
                    }
                }
            }
        } catch (ConfigurationFolderMissingException | \PDOException $ex) {
            die('Error: ' . $ex->getMessage() . PHP_EOL);
        }
    }

    private function generateData(): array
    {
        $data = [];
        $first_name_pool = ['John', 'Jane', 'Jack', 'Jacky', 'Jordan'];
        $last_name_pool = ['Doe', 'Smith', 'Black', 'White', 'Daniels'];
        $email_domain_pool = ['gmail.com', 'yahoo.com'];

        $password = function () {
            $pool = \array_merge(
                \range('A', 'Z'),
                \range('a', 'z'),
                \range(0, 9),
                ['-', '_', '$', '@', '#', '&']
            );
            $password = '';
            $pool_length = \count($pool) - 1;
            while (\strlen($password) < 9) {
                $password .= $pool[\mt_rand(0, $pool_length)];
            }
            return $password;
        };
        foreach ($first_name_pool as $fn) {
            foreach ($last_name_pool as $ln) {
                $name = $fn . ' ' . $ln;
                $email = $fn . '.' . $ln . '@' . $email_domain_pool[mt_rand(0, 1)];
                $pass = $password();
                $data[] = ['name' => $name, 'email' => \strtolower($email), 'password' => $pass];
            }
        }
        return $data;
    }
}
