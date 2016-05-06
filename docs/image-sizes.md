# Image sizes

Image sizes should be registered through the Theme using `AbstractTheme::addImageSize`.

Modules have access to the Theme and can register image sizes using `$this->getTheme()->addImageSize()`.
