<?php
namespace ProductData\Caching\Storages;

use Nette\InvalidArgumentException;
use Nette\Caching\IStorage;
use Nette\Object;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

class FileStorage extends Object implements IStorage
{
	/**
	 * @var \SplFileInfo
	 */
	private $dir;

	public function __construct($dir)
	{
		$dir = new \SplFileInfo($dir);

		FileSystem::createDir($dir, 0755);

		if (!$dir->isDir() || !$dir->isWritable()) {
			throw new InvalidArgumentException(sprintf("Diroctory '%s' does not exists, or not writable", $dir));
		}

		$this->dir = $dir;
	}

	private function getFilename($key)
	{
		return md5($key);
	}

	private function getPath($key)
	{
		return sprintf('%s/%s', $this->dir, substr($this->getFilename($key), 0, 2));
	}

	/**
	 * @param $key
	 * @return \SplFileInfo
	 */
	private function getFile($key)
	{
		return new \SplFileInfo(sprintf('%s/%s', $this->getPath($key), $this->getFilename($key)));
	}

	/**
	 * Read from cache.
	 * @param  string key
	 * @return string|NULL
	 */
	public function read($key)
	{
		$file = $this->getFile($key);

		if ($file->isFile() && $file->isReadable()) {
			return file_get_contents($file);
		}

		return null;
	}

	/**
	 * Prevents item reading and writing. Lock is released by write() or remove().
	 * @param  string key
	 * @return void
	 */
	public function lock($key)
	{
		// TODO: Implement lock() method.
	}

	/**
	 * Writes item into the cache.
	 * @param  string $key
	 * @param  mixed  $data
	 * @param  array  $dependencies
	 * @return void
	 */
	public function write($key, $data, array $dependencies)
	{
		if ($dependencies) {
			trigger_error("Dependencies are note supported in this file storage", E_USER_NOTICE);
		}

		$file = $this->getFile($key);

		FileSystem::createDir($file->getPath(), 0755);
		file_put_contents($file, $data);
	}

	/**
	 * Removes item from the cache.
	 * @param  string key
	 * @return void
	 */
	public function remove($key)
	{
		$file = $this->getFile($key);

		if ($file->isFile()) {
			unlink($file);
		}
	}

	/**
	 * Removes items from the cache by conditions.
	 * @param  array $conditions
	 * @return void
	 */
	public function clean(array $conditions)
	{
		if ($conditions) {
			trigger_error("Conditions are note supported in this file storage", E_USER_NOTICE);
		}

		foreach (Finder::find('*')->from($this->dir) as  $file) {
			/** @var \SplFileInfo $file */
			if ($file->isFile()) {
				unlink($file->getRealPath());
			}
		}
	}
}