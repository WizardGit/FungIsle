int main(int argc, char* argv[])
{
    string filename;

    cout << getline(cin);
	
        
    // Parse the File and read all the input into an array of struct boxes
    string line;

    ifstream InputFile(filename);

    if (InputFile.is_open() == false)
    {
        cout << "Cannot open the file named: " << filename << endl;
        return -1;
    }

    getline(InputFile, line);
    stringstream ss(line);
    int numOfBoxes = 0;
    ss >> numOfBoxes;

    cout << "Number of boxes: " << numOfBoxes << endl;
    Box* boxes = new Box[numOfBoxes];
    cout << "Our boxes: " << endl;

   
    return 0;
}
