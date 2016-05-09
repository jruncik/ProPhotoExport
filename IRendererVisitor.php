<?php

interface IExportVisitor
{
	public function IsGalleryVisible($galleryId);

	public function VisitGaleryBegin();
	public function VisitGalery($galery);
	public function VisitGaleryEnd();

	public function VisitCustomerBegin();
	public function VisitCustomer($order);
	public function VisitCustomerEnd();

	public function VisitPhotoDescriptionBegin();
	public function VisitPhotoDescription($photoDescription);
	public function VisitPhotoDescriptionEnd();

	public function VisitPhotoBegin($photo);
	public function VisitPhoto($photo);
	public function VisitPhotoEnd();
}

interface IElement
{
	public function Accept($visitor);
}

?>