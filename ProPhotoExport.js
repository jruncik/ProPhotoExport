function change(element)
{
	var galleries = getChildNodes(element.value);
}

function getChildNodes(idToShow)
{
	var proPhotoExportElement = document.getElementById("ProPhotoExport");
	var children = new Array();
	for (var child in proPhotoExportElement.childNodes)
	{
		var element = proPhotoExportElement.childNodes[child];
		if (element.nodeType == 1)
		{
			children.push(element.id);
			
			if (idToShow == -1 || element.id == idToShow)
			{
				element.style.display = 'block';
			}
			else
			{
				element.style.display = 'none';
			}
		}
	}
	return children;
}
