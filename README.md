# Provides additional utilities to Image data objects

## Install

```
composer require iqnection-modules/image-utilities
```

## Additional Methods:
- FillFrom & FillMaxFrom: allows you to crop an image from a desired position, rather than just the center

```
$MyImage.CropFrom(300,300,'top')

<%-- pulling position from controller --%>
$MyImage.CropFrom(300,300,$CropPosition)

<img src="$MyImage.CropFrom(300,300,'top').URL" />
```



## Configurations
If not specified in your template, you must add the crop position to your model or controller that's providing the image
Defaults to "center"