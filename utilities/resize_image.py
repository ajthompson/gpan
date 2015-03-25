# resize images to 960x720 for use in a slider for the GPAN website
from PIL import Image
import os, sys, math

# Prints the given message if DEBUG is set to True
def debugPrint(message):
	if (DEBUG):
		print message

# Gets the directory containing this script
def getScriptPath():
	return os.path.dirname(os.path.realpath(sys.argv[0]))

# Resize the image to the global defined size
def resizeImage(filename):
	debugPrint("\t" + filename)
	# open the image and get its width and height
	image = Image.open(filename)
	img_width = image.size[0]
	img_height = image.size[1]

	debugPrint("\t\twidth : %i" % img_width)
	debugPrint("\t\theight: %i" % img_height)

	# check if the height is different
	# if it is, resize so the height is the same
	if (img_height != HEIGHT):
		# compute scaling factor
		scale = HEIGHT / img_height
		new_height = HEIGHT
		# compute new width
		new_width = img_width * scale

		debugPrint("\t\tNew Dimensions:")
		debugPrint("\t\t\tscale : %f" % scale)
		debugPrint("\t\t\twidth : %i" % new_width)
		debugPrint("\t\t\theight: %i" % new_height)


def newName(filename):
	return "resized_" + filename

if __name__ == "__main__":
	# Whether or not debug messages are printed
	global DEBUG
	DEBUG = True

	# The desired width and height
	global HEIGHT
	global WIDTH
	HEIGHT = 720.0
	WIDTH = 960.0
	debugPrint("Default height: %d" % HEIGHT)
	debugPrint("Default width : %d" % WIDTH)

	# get the directory this file is in
	path = getScriptPath()
	debugPrint(path)

	# iterate through the directory
	for filename in os.listdir(path):
		# check the filetype
		# Right now it's only tested with jpg, add support for png and other image formats maybe?
		if filename.endswith(".jpg"):
			resizeImage(filename)