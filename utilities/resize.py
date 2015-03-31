# resize.py
# 
# Resizes images to a consistant 1920x1080 prioritizing height if used with no
# command line arguments, capable of being used for the slider on the gpan
# website.
# 
# If used with command line arguments can resize images to a specified height
# and width, prioritizing the one used first.
# 
# Usage:
# 
# 	python resize.py [-w|--width dimension1 -h|--height dimension2] [-d|--debug] [-u|--usage|--help]
# 	
# 		OR
# 	
# 	python resize.py [-h|--height dimension1 -w|--width dimension2] [-d|--debug] [-u|--usage|--help]
# 	
# 		-w|--width 			Specify the width
# 							If the -w argument comes before -h, the width is prioritized
# 		-h|--height			Specify the height
# 							If the -h argument comes before -w, the height is prioritized
# 		dimension(1|2)		specifies the number of pixels for the preceding argument
# 		-d|--debug 			Print debug messages
# 		-u|--usage|--help   Print usage information
# 		
# 		
# 
# Ex:
# 
# 	python resize.py -w 200 -h 50
# 	
# 	This will scale an image so that it's with is 200px. If the height is still
# 	larger than 50 px, the top and bottom will be cropped equally. If the
# 	height is less than 50, the image will be in the center of a 200x50px
# 	transparent background.
# 	
# Ex2:
# 
# 	python resize.py -h 50 -w 200 -d
# 	
# 	This will scale an image so that it's height is 50px. If the width is still
# 	larger than 200 px, the left and right will be cropped equally. If the
# 	width is less than 200, the image will be in the center of a 200x50px
# 	transparent background. Debug messages will also be printer
# 	
# 	@author Alec Thompson
# 	@date Mar 27, 2015
import os, sys, math, datetime
from PIL import Image

# Enumeration of Width and Height
# Allows readable determination of the priority mode of the program
class Priority:
	Width, Height = range(2)

def resizeImage(filename, width, height, mode):
	# open the image and get its width and height
	image = Image.open(filename)
	img_width = image.size[0]
	img_height = image.size[1]
	image = image.convert("RGBA")
	resized = image

	debugPrint("Image %d Name: %s" % (counter, filename), 1)
	printDimensions(img_width, img_height, 2, "Initial Dimensions")
	debugPrint("", 2)

	if mode == Priority.Width:
		if img_width != width:
			# determine scaler type
			if img_width < width:
				scalerType = Image.BICUBIC
			else:
				scalerType = Image.ANTIALIAS

			# compute scaling factor
			scale = float(width) / img_width
			debugPrint("Scale Factor: %f" % scale, 2)
			img_width = width
			img_height = int(math.floor(img_height * scale))
			printDimensions(img_width, img_height, 2, "New Dimensions")
			debugPrint("", 2)

			# resize the image
			resized = image.resize((img_width, img_height), scalerType)
			img_width = resized.size[0]
			img_height = resized.size[1]

			printDimensions(img_width, img_height, 2, "Scaled Dimensions")
			debugPrint("", 2)

		# determine if the width needs to be cropped or expanded
		if img_height < height:		# paste over a correct sized image
			new_img = Image.new(image.mode, (width, height), (255, 255, 255, 0))

			# compute where to place the upper left corner
			corner = (0, (height - img_height) / 2)

			# paste the image
			new_img.paste(resized, corner)
			resized = new_img

			img_width = resized.size[0]
			img_height = resized.size[1]

			printDimensions(img_width, img_height, 2, "Pasted Dimensions")
			debugPrint("", 2)

		elif img_height > height:	# crop to the correct size
			crop_offset = (img_height - height) / 2
			crop_box = (0, crop_offset, img_width, img_height - crop_offset)
			resized = resized.crop(crop_box)

			img_width = resized.size[0]
			img_height = resized.size[1]

			printDimensions(img_width, img_height, 2, "Cropped Dimensions")
			debugPrint("", 2)

	elif mode == Priority.Height:
		if img_height != height:
			# determine scaler type
			if img_height < height:
				scalerType = Image.BICUBIC
			else:
				scalerType = Image.ANTIALIAS

			# compute scaling factor
			scale = float(height) / img_height
			debugPrint("Scale Factor: %f" % scale, 2)
			img_height = height
			img_width = int(math.floor(img_width * scale))
			printDimensions(img_width, img_height, 2, "New Dimensions")
			debugPrint("", 2)

			# resize the image
			resized = image.resize((img_width, img_height), scalerType)
			img_width = resized.size[0]
			img_height = resized.size[1]

			printDimensions(img_width, img_height, 2, "Scaled Dimensions")
			debugPrint("", 2)

		# determine if the width needs to be cropped or expanded
		if img_width < width:		# paste over a correct sized image
			new_img = Image.new(image.mode, (width, height), (255, 255, 255, 0))

			# compute where to place the upper left corner
			corner = ((width - img_width) / 2, 0)

			# paste the image
			new_img.paste(resized, corner)
			resized = new_img

			img_width = resized.size[0]
			img_height = resized.size[1]

			printDimensions(img_width, img_height, 2, "Pasted Dimensions")
			debugPrint("", 2)

		elif img_width > width:		# crop to correct size
			crop_offset = (img_width - width) / 2
			crop_box = (crop_offset, 0, img_width - crop_offset, img_height)
			resized = resized.crop(crop_box)

			img_width = resized.size[0]
			img_height = resized.size[1]

			printDimensions(img_width, img_height, 2, "Cropped Dimensions")
			debugPrint("", 2)

	name = newName()
	debugPrint("New Filename: %s" % name, 2)

	# print final dimensions
	printDimensions(img_width, img_height, 2, "Final Dimensions")
	debugPrint("", 2)

	# save the image
	resized.save(name)


# Generates a filename for the resized image.
# 
# filename	the name of 
def newName():
	global counter
	date = datetime.datetime.now()
	name = "rsz%04d_%d_%02d_%02d_%02d_%02d_%02d.png" % (counter, date.year, date.month, date.day, date.hour, date.minute, date.second)
	counter = counter + 1
	return name

# Checks the filename agains known image extensions
def isPicture(filename):
	return filename.endswith(".bmp") or filename.endswith(".eps") or filename.endswith(".gif") or filename.endswith(".im") or filename.endswith(".jpeg") or filename.endswith(".jpg") or filename.endswith(".j2k") or filename.endswith(".j2p") or filename.endswith(".jpx") or filename.endswith(".tiff") or filename.endswith(".jfif") or filename.endswith(".jif") or filename.endswith(".png")

# handles the arguments and returns a list containing the defaults or the changed values
def handleArguments():
	# set default values
	path = os.path.dirname(os.path.realpath(sys.argv[0]))
	width = 1920
	height = 1080
	mode = Priority.Height
	debug = False

	# iterate through arguments
	i = 1
	while i < len(sys.argv):
		arg = sys.argv[i]

		# check if is a help command
		if arg == "-u" or arg == "--usage" or arg == "--help":
			# print usage and exit
			printUsage()
		elif arg == "-d" or arg == "--debug":
			debug = True
		elif arg == "-w" or arg == "--width":
			if not i + 3 < len(sys.argv):
				print "Wrong number of dimension arguments"
				printUsage()

			# get the width value
			i = i + 1
			arg = sys.argv[i]
			try:
				width = int(arg)
			except ValueError:
				print "Width %s not an integer" % arg
				printUsage()

			# get the height declaration
			i = i + 1
			arg = sys.argv[i]
			if (arg != "-h" and arg != "--height"):
				print "Height not declared"
				printUsage()

			# get the height
			i = i + 1
			arg = sys.argv[i]
			try:
				height = int(arg)
			except ValueError:
				print "Height %s is not an integer" % arg
				printUsage()

			# set the mode
			mode = Priority.Width
		elif arg == "-h" or arg == "--height":
			if not i + 3 < len(sys.argv):
				print "Wrong number of dimension arguments"
				printUsage()

			# get the width value
			i = i + 1
			arg = sys.argv[i]
			try:
				height = int(arg)
			except ValueError:
				print "Height %s not an integer" % arg
				printUsage()

			# get the height declaration
			i = i + 1
			arg = sys.argv[i]
			if (arg != "-w" and arg != "--width"):
				print "Width not declared"
				printUsage()

			# get the height
			i = i + 1
			arg = sys.argv[i]
			try:
				width = int(arg)
			except ValueError:
				print "Width %s is not an integer" % arg
				printUsage()
			# set the mode
			mode = Priority.Height
		else:
			print "Argument not recognized"
			printUsage()
		# increment iterator
		i = i + 1

	# build the list to be returned
	retvals = list()
	retvals.append(path)
	retvals.append(width)
	retvals.append(height)
	retvals.append(mode)
	retvals.append(debug)

	return retvals

def modeToString(mode):
	if mode == 0:
		return "WIDTH"
	elif mode == 1:
		return "HEIGHT"

# Prints the given dimensions with the given descriptor
#
# Ex: 	
# 		printDimensions("Scaled Dimensions", 1920, 1080, 0)
# 		
# 		prints
# 
# 		Scaled Dimensions:
# 			Width : 1920
# 			Height: 1080
# 			
# descriptor	a descriptor for the pair of dimensions
# width 		the specified width
# height 		the specified height
# numTabs		the number of indentations
def printDimensions(width, height, numTabs, descriptor="Dimensions"):
	debugPrint(descriptor + ":", numTabs)
	debugPrint("Width : %d" % width, numTabs + 1)
	debugPrint("Height: %d" % height, numTabs + 1)

# Print a message if debug mode is enabled, indented by the specified number
# of tabs
# 
# message 	the message to be printed
# numTabs	the number of tabs to be indented by
def debugPrint(message, numTabs):
	if (DEBUG):
		tabs = ""
		for i in range(0, numTabs):
			tabs = tabs + "\t"
		print tabs + message

# prints the usage of the program, and then exit
def printUsage():
	print "Usage:"
	print "\tpython resize.py [-w|--width dimension1 -h|--height dimension2] [-d|--debug] [-u|--usage|--help]"
	print ""
	print "\t\tOR"
	print ""
	print "\tpython resize.py [-h|--height dimension1 -w|--width dimension2] [-d|--debug] [-u|--usage|--help]"
	print ""
	print "\t\t-w|--width         specify the width of the scaled image in pixels"
	print "\t\t                   If width is specified before height, width will be prioritized"
	print "\t\t-h|--height        Specify the height of the scaled image in pixels"
	print "\t\t                   If height is specified before width, height will be prioritized"
	print "\t\tdimension(1|2)     The height/width being specified"
	print "\t\t-d|--debug         Print debug messages"
	print "\t\t-u|--usage|--help  Print this usage information"
	print "Exiting program now"
	sys.exit()

if __name__ == "__main__":
	# set global values
	global DEBUG 		# whether or not debug messaged will be printed
	global counter		# stores the number of images processed so that
						# each is given a unique number
	
	# initialize counter to 0
	counter = 0

	# handle arguments
	path, width, height, mode, DEBUG = handleArguments()

	# print debug values
	debugPrint("Input Values:", 0)
	debugPrint("Containing Folder: %s" % path, 1)
	printDimensions(width, height, 1)
	debugPrint("Mode:  %s" % modeToString(mode), 1)
	debugPrint("DEBUG: %s" % DEBUG, 1)

	debugPrint("", 0)
	debugPrint("///////////////////////////", 0)
	debugPrint("//// Converting Images ////", 0)
	debugPrint("///////////////////////////", 0)
	debugPrint("", 0)

	for filename in os.listdir(path):
		if isPicture(filename.lower()) and not filename.startswith("rsz"):
			resizeImage(filename, width, height, mode)