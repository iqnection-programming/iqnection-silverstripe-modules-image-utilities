<?php

namespace IQnection\ImageUtilities\Assets;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Assets\ImageManipulation;
use SilverStripe\Assets\Storage\AssetContainer;
use SilverStripe\Assets\Image_Backend;

class ImageUtilities extends DataExtension implements AssetContainer
{
	use ImageManipulation;

	protected function formatCropPosition($position)
	{
		$position = preg_replace('/center|middle/','',strtolower($position));
		$position = preg_replace('/\s/','-',$position);
		$position = preg_replace('/^-|-$/','',$position);
		return (empty($position)) ? 'center' : $position;
	}

	public function FillFrom($width, $height, $position = 'center')
    {
		$position = $this->formatCropPosition($position);
        $width = $this->castDimension($width, 'Width');
        $height = $this->castDimension($height, 'Height');
        $variant = $this->variantName(__FUNCTION__, $width, $height, $position);
        return $this->owner->manipulateImage($variant, function (Image_Backend $backend) use ($width, $height, $position) {
            if ($backend->getWidth() === $width && $backend->getHeight() === $height) {
                return $this;
            }
			$clone = clone $backend;
            $resource = clone $backend->getImageResource();
			$resource->fit($width, $height, null, $position);
			$clone->setImageResource($resource);
			return $clone;
        });
    }

	public function FillMaxFrom($width, $height, $position = 'center')
    {
		$position = $this->formatCropPosition($position);
        $width = $this->castDimension($width, 'Width');
        $height = $this->castDimension($height, 'Height');
        $variant = $this->variantName(__FUNCTION__, $width, $height, $position);
        return $this->owner->manipulateImage($variant, function (Image_Backend $backend) use ($width, $height, $position) {
            // Validate dimensions
            $currentWidth = $backend->getWidth();
            $currentHeight = $backend->getHeight();
            if (!$currentWidth || !$currentHeight) {
                return null;
            }
            if ($currentWidth === $width && $currentHeight === $height) {
                return $this;
            }

            // Compare current and destination aspect ratios
            $imageRatio = $currentWidth / $currentHeight;
            $cropRatio = $width / $height;

			$clone = clone $backend;
            $resource = clone $backend->getImageResource();

            if ($cropRatio < $imageRatio && $currentHeight < $height) {
                // Crop off sides
				$resource->fit(round($currentHeight * $cropRatio), $currentHeight, null, $position);
            } elseif ($currentWidth < $width) {
                // Crop off top/bottom
				$resource->fit($currentWidth, round($currentWidth / $cropRatio), null, $position);
            } else {
                // Crop on both
				$resource->fit($width, $height, null, $position);
            }

			$clone->setImageResource($resource);
			return $clone;
        });
    }

	public function setFromString($data, $filename, $hash = null, $variant = null, $config = array())
	{
		return $this->owner->setFromString($data, $filename, $hash = null, $variant = null, $config = array());
	}

	public function setFromLocalFile($path, $filename = null, $hash = null, $variant = null, $config = array())
	{
		return $this->owner->setFromLocalFile($path, $filename = null, $hash = null, $variant = null, $config = array());
	}

	public function setFromStream($stream, $filename, $hash = null, $variant = null, $config = array())
	{
		return $this->owner->setFromStream($stream, $filename, $hash = null, $variant = null, $config = array());
	}

	public function getVisibility()
	{
		return $this->owner->getVisibility();
	}

	public function deleteFile()
	{
		return $this->owner->deleteFile();
	}

	public function renameFile($newName)
	{
		return $this->owner->renameFile($newName);
	}

    public function copyFile($newName)
	{
		return $this->owner->copyFile($newName);
	}

    public function publishFile()
	{
		return $this->owner->publishFile();
	}

    public function protectFile()
	{
		return $this->owner->protectFile();
	}

    public function grantFile()
	{
		return $this->owner->grantFile();
	}

    public function revokeFile()
	{
		return $this->owner->revokeFile();
	}

    public function canViewFile()
	{
		return $this->owner->canViewFile();
	}

	public function getString()
	{
		return $this->owner->getString();
	}

    public function getStream()
	{
		return $this->owner->getStream();
	}

    public function getURL($grant = true)
	{
		return $this->owner->getURL($grant = true);
	}

    public function getAbsoluteURL()
	{
		return $this->owner->getAbsoluteURL();
	}

    public function getMetaData()
	{
		return $this->owner->getMetaData();
	}

    public function getMimeType()
	{
		return $this->owner->getMimeType();
	}

    public function getAbsoluteSize()
	{
		return $this->owner->getAbsoluteSize();
	}

    public function exists()
	{
		return $this->owner->exists();
	}

    public function getFilename()
	{
		return $this->owner->getFilename();
	}

	public function getHash()
	{
		return $this->owner->getHash();
	}

    public function getVariant()
	{
		return $this->owner->getVariant();
	}

    public function getIsImage()
	{
		return $this->owner->getIsImage();
	}
}