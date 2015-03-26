# resize images to 960x720 for use in a slider for the GPAN website
from PIL import Image
import os, sys, math, datetime

# Prints the given message if DEBUG is set to True
def debugPrint(message):
	if (DEBUG):
		print message

# Gets the directory containing this script
def getScriptPath():
	return os.path.dirname(os.path.realpath(sys.argv[0]))

def isPicture(filename):
	return filename.endswith(".bmp") or filename.endswith(".eps") or filename.endswith(".gif") or filename.endswith(".im") or filename.endswith(".jpeg") or filename.endswith(".jpg") or filename.endswith(".j2k") or filename.endswith(".j2p") or filename.endswith(".jpx") or filename.endswith(".tiff") or filename.endswith(".jfif") or filename.endswith(".jif") or filename.endswith(".png")

# Resize the image to the global defined size
def resizeImage(filename):
	debugPrint("\t" + filename)
	# open the image and get its width and height
	image = Image.open(filename)
	img_width = image.size[0]
	img_height = image.size[1]
	image = image.convert("RGBA")

	debugPrint("\t\twidth : %i" % img_width)
	debugPrint("\t\theight: %i" % img_height)
	debugPrint("\t\tmode  : %s" % image.mode)

	# check if the height is different
	# if it is, resize so the height is the same
	if (img_height != HEIGHT):
		# compute scaling factor
		scale = HEIGHT / img_height
		new_height = int(math.floor(HEIGHT))
		# compute new width
		new_width = int(math.floor(img_width * scale))

		debugPrint("\t\tNew Dimensions:")
		debugPrint("\t\t\tscale : %f" % scale)
		debugPrint("\t\t\twidth : %i" % new_width)
		debugPrint("\t\t\theight: %i" % new_height)

		if (img_height < HEIGHT):
			scalerType = Image.BICUBIC
		else:
			scalerType = Image.ANTIALIAS

		# scale the image
		resized = image.resize((new_width, new_height), scalerType)

		debugPrint("\t\tScaled Dimensions:")
		debugPrint("\t\t\twidth : %i" % resized.size[0])
		debugPrint("\t\t\theight: %i" % resized.size[1])

		if (new_width < WIDTH):
			# paste the picture over the center of 
			# a new transparent WIDTH x HEIGHT image
			bg = Image.new(image.mode, (int(WIDTH), int(HEIGHT)), None)

			# compute upper-left corner
			corner = ((int(WIDTH) - resized.size[0]) / 2, 0)

			# paste the image
			bg.paste(resized, corner)
			resized = bg
		elif (new_width > WIDTH):
			# crop the picture
			crop_offset = int((img_width - WIDTH) / 2)
			left = crop_offset
			if (left < 0):
				left = 0
			right = img_width - crop_offset
			if (right > img_width):
				right = img_width
			top = 0
			bottom = int(HEIGHT)
			debugPrint("\t\tCrop box:")
			debugPrint("\t\t\tleft  : %d" % left)
			debugPrint("\t\t\tright : %d" % right)
			debugPrint("\t\t\ttop   : %d" % top)
			debugPrint("\t\t\tbottom: %d" % bottom)
			crop_box = (left, top, right, bottom)
			resized = image.crop(crop_box)

		# save the image
		resized.save(newName(filename))

	elif (img_width < WIDTH):
		# paste the picture over the center of 
		# a new transparent WIDTH x HEIGHT image
		##
		# Create a new background
		bg = Image.new("RGBA", (WIDTH, HEIGHT), None)

		# compute upper-left corner
		corner = ((int(WIDTH) - resized.size[0]) / 2, 0)

		# paste the image
		bg.paste(resized, corner)
		resized = bg
		resized.save(newName(filename))

	elif (img_width > WIDTH):
		crop_offset = int((img_width - WIDTH) / 2)
		left = crop_offset
		if (left < 0):
			left = 0
		right = img_width - crop_offset
		if (right > img_width):
			right = img_width
		top = 0
		bottom = int(HEIGHT)
		debugPrint("\t\tCrop box:")
		debugPrint("\t\t\tleft  : %d" % left)
		debugPrint("\t\t\tright : %d" % right)
		debugPrint("\t\t\ttop   : %d" % top)
		debugPrint("\t\t\tbottom: %d" % bottom)
		crop_box = (left, top, right, bottom)
		resized = image.crop(crop_box)
		resized.save(newName(filename))


def newName(filename):
	global counter
	name = "rsz%03d_%d_%02d_%02d.jpg" % (counter, date.year, date.month, date.day) 
	counter = counter + 1
	return name

if __name__ == "__main__":
	# Whether or not debug messages are printed
	global DEBUG
	# SET TO TRUE TO PRINT DEBUG MESSAGES
	DEBUG = False

	# The desired width and height
	global HEIGHT
	global WIDTH
	HEIGHT = 1080.0
	WIDTH = 1920.0

	debugPrint("Default height: %d" % HEIGHT)
	debugPrint("Default width : %d" % WIDTH)

	global counter
	global date
	counter = 0
	date = datetime.datetime.now()

	# get the directory this file is in
	path = getScriptPath()
	debugPrint(path)

	# iterate through the directory
	for filename in os.listdir(path):
		# Right now it's only tested with jpg, add support for png and other image formats maybe?
		if isPicture (filename) and not filename.startswith("rsz"):
			resizeImage(filename)